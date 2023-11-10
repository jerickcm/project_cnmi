<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Company;
use App\Models\Logs;
use App\Models\Role as RoleConstant;
use App\Events\UserLogsEvent;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\Employers;
use App\Models\DolStaff;


use App\Models\EmailLogs;
use App\Events\SendEmailEvent;

class UserController extends Controller
{
    const SUPERADMIN = 1;
    const ADMIN = 2;
    const USER = 3;

    public function index()
    {
        return UserResource::collection(user::all());
    }

    public function index_user($id)
    {

        $user = User::findOrFail($id);
        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
            return  UserResource::collection(User::get());
        } else if ($user->hasPermissionTo('Action Show-All User')) {

            $all = User::get();
            $admin = User::whereHas('roles', function ($query) {
                $query->where('name', 'ADMIN');
            })->get();
            $superadmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'SUPERADMIN');
            })->get();
            $all = $all->diff($admin);
            $minus = $all->diff($superadmin);

            return response()->json([
                'data' => $minus->toArray()
            ]);
        } else {
            return  UserResource::collection(User::where('id', $id)->get());
        }
    }

    public function store(UserRequest $request)
    {

        if ($request->role == SELF::USER && $request->superadmin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have superadmin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {

            $user_data = User::findOrfail($request->user_id);

            event(new UserLogsEvent($user_data->id, Logs::TYPE_CREATE_USER, [
                'email'  =>   $user_data->email,
                'create_email'  =>   $request->email,
            ]));

            event(new SendEmailEvent($user_data->id, EmailLogs::TYPE_CREATE_USER, [

                'email'  =>   $user_data->email,
                'create_email'  => $request->email,
                'password' => $request->password,
                'subject' => "Create user from CNMI dol - info@cnmidol.gov.mp",
            ]));

            $fullname = $request->last_name . "," . $request->first_name . " " . $request->middle_name . " " . $request->name_suffix;

            $company_id_store = null;

            $company = Company::where('id', $request->company_id['value'])->where('company_name', $request->company_id['label'])->first();
            if (!$company) {

                $newcompany = Company::create([
                    'company_name' => $request->company_id['label']
                ]);
                $company_id_store = $newcompany->id;
                $user = User::findOrfail(Auth::guard('web')->user()->id);



            } else {
                $company_id_store = $company->id;
            }

            $user = User::create([

                'name' =>  $fullname,
                'full_name' => $fullname,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $company_id_store

            ]);

            $employer_verified = 0;
            $dol_verified = 0;

            if ($request->role) {

                $role = Role::find($request->role);

                if ($role->id == 3) {
                    $employer_verified = 1;
                }

                if ($role->id == 4) {
                    $dol_verified = 1;
                }
            }

            Employers::create([
                'user_id' => $user->id,
                'verified' =>  $employer_verified,
                'company_id' => $company_id_store
            ]);

            DolStaff::create([
                'user_id' => $user->id,
                'verified' => $dol_verified,
                'company_id' => $company_id_store
            ]);

            if ($request->superadmin == 1) {

                $user->assignRole('SUPERADMIN');
                /** Page Access */
                $user->givePermissionTo('Access-Page-User');
                $user->givePermissionTo('Access-Page-Dashboard');
                $user->givePermissionTo('Access-Page-Logs');

                /** Page Actions */
                $user->givePermissionTo('Action Edit Permission');

                /* User Permission */
                $user->givePermissionTo('Action Delete User');
                $user->givePermissionTo('Action Create User');
                $user->givePermissionTo('Action Edit User');
                $user->givePermissionTo('Action Show-All User');

                /* Admin and SuperAdmin Permission */
                $user->givePermissionTo('Action Settings Roles');
                $user->givePermissionTo('Action Download User');
                $user->givePermissionTo('Action Download Logs');

                /* Report Permission */
                $user->givePermissionTo('Access-Page-Report');
                $user->givePermissionTo('Action Print Report');
                $user->givePermissionTo('Action Download Report');
                $user->givePermissionTo('Action Generate Report');

                /* Edit Company Permission */
                $user->givePermissionTo('Access-Page-Company');
                $user->givePermissionTo('Action Edit Company');
                $user->givePermissionTo('Action Delete Company');
                $user->givePermissionTo('Action Print Company');
                $user->givePermissionTo('Action Download Company');
                $user->givePermissionTo('Action Create Company');

                /* Edit Categories Permission */
                $user->givePermissionTo('Access-Page-Categories');
                $user->givePermissionTo('Action Edit Categories-Type');
                $user->givePermissionTo('Action Delete Categories-Type');
                $user->givePermissionTo('Action Edit Categories-Industry');
                $user->givePermissionTo('Action Delete Categories-Industry');
                $user->givePermissionTo('Action Print Categories');
                $user->givePermissionTo('Action Download Categories');
                $user->givePermissionTo('Action Create Categories-Type');
                $user->givePermissionTo('Action Create Categories-Industry');
            }

            if ($request->admin == 1) {

                $user->assignRole('ADMIN');
                /** Page Access */
                $user->givePermissionTo('Access-Page-User');
                $user->givePermissionTo('Access-Page-Dashboard');
                $user->givePermissionTo('Access-Page-Logs');
                /** Page Actions */
                $user->givePermissionTo('Action Edit Permission');
                /* User Permission */
                $user->givePermissionTo('Action Delete User');
                $user->givePermissionTo('Action Create User');
                $user->givePermissionTo('Action Edit User');
                $user->givePermissionTo('Action Show-All User');
                /* Admin and SuperAdmin Permission */
                $user->givePermissionTo('Action Settings Roles');
                $user->givePermissionTo('Action Download User');
                $user->givePermissionTo('Action Download Logs');
                /* Report Permission */
                $user->givePermissionTo('Access-Page-Report');
                $user->givePermissionTo('Action Print Report');
                $user->givePermissionTo('Action Download Report');
                $user->givePermissionTo('Action Generate Report');

                /* Edit Company Permission */
                $user->givePermissionTo('Access-Page-Company');
                $user->givePermissionTo('Action Edit Company');
                $user->givePermissionTo('Action Delete Company');
                $user->givePermissionTo('Action Print Company');
                $user->givePermissionTo('Action Download Company');
                $user->givePermissionTo('Action Create Company');

                /* Edit Categories Permission */
                $user->givePermissionTo('Access-Page-Categories');
                $user->givePermissionTo('Action Edit Categories-Type');
                $user->givePermissionTo('Action Delete Categories-Type');
                $user->givePermissionTo('Action Edit Categories-Industry');
                $user->givePermissionTo('Action Delete Categories-Industry');
                $user->givePermissionTo('Action Print Categories');
                $user->givePermissionTo('Action Download Categories');
                $user->givePermissionTo('Action Create Categories-Type');
                $user->givePermissionTo('Action Create Categories-Industry');
            } else {

                // const EMPLOYER = 3;
                // const DOLESTAFF = 4;

                if ($request->role) {
                    $role = Role::find($request->role);
                    $user->assignRole($role->name);

                    /** Page Access */
                    $user->givePermissionTo('Access-Page-User');
                    $user->givePermissionTo('Access-Page-Dashboard');
                    $user->givePermissionTo('Access-Page-Logs');

                    if ($role->id == RoleConstant::DOLESTAFF) {
                        /** Page Actions */
                        $user->givePermissionTo('Action Edit Permission');

                        $user->givePermissionTo('Action Delete User');
                        $user->givePermissionTo('Action Create User');
                        $user->givePermissionTo('Action Edit User');
                        $user->givePermissionTo('Action Show-All User');

                        $user->givePermissionTo('Action Settings Roles');
                        $user->givePermissionTo('Action Download User');
                        $user->givePermissionTo('Action Download Logs');

                        /* Report Permission */
                        $user->givePermissionTo('Access-Page-Report');
                        $user->givePermissionTo('Action Print Report');
                        $user->givePermissionTo('Action Download Report');
                        $user->givePermissionTo('Action Generate Report');

                        /* Edit Company Permission */
                        $user->givePermissionTo('Access-Page-Company');
                        $user->givePermissionTo('Action Edit Company');
                        $user->givePermissionTo('Action Delete Company');
                        $user->givePermissionTo('Action Print Company');
                        $user->givePermissionTo('Action Download Company');
                        $user->givePermissionTo('Action Create Company');

                        /* Edit Categories Permission */
                        $user->givePermissionTo('Access-Page-Categories');
                        $user->givePermissionTo('Action Edit Categories-Type');
                        $user->givePermissionTo('Action Delete Categories-Type');
                        $user->givePermissionTo('Action Edit Categories-Industry');
                        $user->givePermissionTo('Action Delete Categories-Industry');
                        $user->givePermissionTo('Action Print Categories');
                        $user->givePermissionTo('Action Download Categories');
                        $user->givePermissionTo('Action Create Categories-Type');
                        $user->givePermissionTo('Action Create Categories-Industry');
                    }
                }
            }

            event(new Registered($user));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }
        DB::commit();

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {

        if ($request->role == SELF::USER  && $request->superadmin == 1) {
            return response()->json([
                'errors' => ['role' => ["User role not permitted to have superadmin role."]],
                'message' => "Password does't match",
            ], 422);
        }

        // const SUPERADMIN = 1;
        // const ADMIN = 2;
        // const EMPLOYER = 3;
        // const DOLESTAFF = 4;

        DB::beginTransaction();

        try {

            if ($request->id == 1 || $request->id == 2) {
                $full_name = $request->last_name . " " . $request->first_name . " " . $request->middle_name . " " . $request->name_suffix;
            } else {
                $full_name = $request->last_name . "," . $request->first_name . " " . $request->middle_name . " " . $request->name_suffix;
            }

            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->name_suffix = $request->name_suffix;
            $user->full_name = $full_name;
            $user->save();

            $user->update($request->validated());
            $user->syncRoles([]);
            $user->syncPermissions([]);


            $employer_verified = 0;
            $dol_verified = 0;

            /** 1. Project Role Assignment */
            if ($request->role) {

                $role_new = Role::find($request->role);

                if ($role_new->id == RoleConstant::EMPLOYER) {
                    $employer_verified = 1;
                } elseif ($role_new->id == RoleConstant::DOLESTAFF) {
                    $dol_verified = 1;
                }

                if ($request->role) {

                    $role = Role::find($request->role);
                    $user->assignRole($role->name);

                    $user->givePermissionTo('Access-Page-User');
                    $user->givePermissionTo('Access-Page-Dashboard');
                    $user->givePermissionTo('Action Edit User');

                    if ($role->id == RoleConstant::DOLESTAFF) {
                        /** Page Actions */
                        $user->givePermissionTo('Action Edit Permission');
                        /* User Permission */
                        $user->givePermissionTo('Action Delete User');
                        $user->givePermissionTo('Action Create User');
                        $user->givePermissionTo('Action Edit User');
                        $user->givePermissionTo('Action Show-All User');
                        /* Admin and SuperAdmin Permission */
                        $user->givePermissionTo('Action Settings Roles');
                        $user->givePermissionTo('Action Download User');
                        $user->givePermissionTo('Action Download Logs');

                        /* Report Permission */
                        $user->givePermissionTo('Access-Page-Report');
                        $user->givePermissionTo('Action Print Report');
                        $user->givePermissionTo('Action Download Report');
                        $user->givePermissionTo('Action Generate Report');

                        /* Edit Company Permission */
                        $user->givePermissionTo('Access-Page-Company');
                        $user->givePermissionTo('Action Edit Company');
                        $user->givePermissionTo('Action Delete Company');
                        $user->givePermissionTo('Action Print Company');
                        $user->givePermissionTo('Action Download Company');
                        $user->givePermissionTo('Action Create Company');

                        /* Edit Categories Permission */
                        $user->givePermissionTo('Access-Page-Categories');
                        $user->givePermissionTo('Action Edit Categories-Type');
                        $user->givePermissionTo('Action Delete Categories-Type');
                        $user->givePermissionTo('Action Edit Categories-Industry');
                        $user->givePermissionTo('Action Delete Categories-Industry');
                        $user->givePermissionTo('Action Print Categories');
                        $user->givePermissionTo('Action Download Categories');
                        $user->givePermissionTo('Action Create Categories-Type');
                        $user->givePermissionTo('Action Create Categories-Industry');
                    }
                }

                $employers = Employers::where('user_id', $request->id)->first();
                $employers->verified = $employer_verified;
                $employers->save();

                $dol = DolStaff::where('user_id', $request->id)->first();
                $dol->verified = $dol_verified;
                $dol->save();

                $user->assignRole($role_new->name);

                $user_change = User::findOrfail($request->id);
                $user_action = User::findOrfail($request->user_id);

                $full_name = $request->last_name . "," . $request->first_name . " " . $request->middle_name . " " . $request->name_suffix;

                event(new UserLogsEvent($request->user_id, Logs::TYPE_UPDATE_USER, [
                    'email'  =>   $user_action->email,
                    'email_update'  =>   $user_change->email,
                    'name'  =>  $full_name,
                    'role' => $role_new->name
                ]));
            }

            /** 2. Superadmin Role/Permission */

            if (isset($request->superadmin)) {

                if ($request->superadmin == true) {

                    $user->assignRole('SUPERADMIN');
                    /** Page Access */
                    $user->givePermissionTo('Access-Page-User');
                    $user->givePermissionTo('Access-Page-Dashboard');
                    $user->givePermissionTo('Access-Page-Logs');
                    /** Page Actions */
                    $user->givePermissionTo('Action Edit Permission');
                    /* User Permission */
                    $user->givePermissionTo('Action Delete User');
                    $user->givePermissionTo('Action Create User');
                    $user->givePermissionTo('Action Edit User');
                    $user->givePermissionTo('Action Show-All User');
                    /* Logs Permission */

                    /* Admin and SuperAdmin Permission */
                    $user->givePermissionTo('Action Settings Roles');
                    $user->givePermissionTo('Action Download User');
                    $user->givePermissionTo('Action Download Logs');

                    /* Report Permission */
                    $user->givePermissionTo('Access-Page-Report');
                    $user->givePermissionTo('Action Print Report');
                    $user->givePermissionTo('Action Download Report');
                    $user->givePermissionTo('Action Generate Report');

                    /* Edit Company Permission */
                    $user->givePermissionTo('Access-Page-Company');
                    $user->givePermissionTo('Action Edit Company');
                    $user->givePermissionTo('Action Delete Company');
                    $user->givePermissionTo('Action Print Company');
                    $user->givePermissionTo('Action Download Company');
                    $user->givePermissionTo('Action Create Company');

                    /* Edit Categories Permission */
                    $user->givePermissionTo('Access-Page-Categories');
                    $user->givePermissionTo('Action Edit Categories-Type');
                    $user->givePermissionTo('Action Delete Categories-Type');
                    $user->givePermissionTo('Action Edit Categories-Industry');
                    $user->givePermissionTo('Action Delete Categories-Industry');
                    $user->givePermissionTo('Action Print Categories');
                    $user->givePermissionTo('Action Download Categories');
                    $user->givePermissionTo('Action Create Categories-Type');
                    $user->givePermissionTo('Action Create Categories-Industry');
                }
            }

            /** 3. Admin Role/Permission */

            if (isset($request->admin)) {

                if ($request->admin == true) {

                    $user->assignRole('ADMIN');
                    /** Page Access */
                    $user->givePermissionTo('Access-Page-User');
                    $user->givePermissionTo('Access-Page-Dashboard');
                    $user->givePermissionTo('Access-Page-Logs');
                    /** Page Actions */
                    $user->givePermissionTo('Action Edit Permission');
                    /* User Permission */
                    $user->givePermissionTo('Action Delete User');
                    $user->givePermissionTo('Action Create User');
                    $user->givePermissionTo('Action Edit User');
                    $user->givePermissionTo('Action Show-All User');
                    /* Logs Permission */

                    /* Admin and SuperAdmin Permission */
                    $user->givePermissionTo('Action Settings Roles');
                    $user->givePermissionTo('Action Download User');
                    $user->givePermissionTo('Action Download Logs');

                    /* Report Permission */
                    $user->givePermissionTo('Access-Page-Report');
                    $user->givePermissionTo('Action Print Report');
                    $user->givePermissionTo('Action Download Report');
                    $user->givePermissionTo('Action Generate Report');

                    /* Edit Company Permission */
                    $user->givePermissionTo('Access-Page-Company');
                    $user->givePermissionTo('Action Edit Company');
                    $user->givePermissionTo('Action Delete Company');
                    $user->givePermissionTo('Action Print Company');
                    $user->givePermissionTo('Action Download Company');
                    $user->givePermissionTo('Action Create Company');

                    /* Edit Categories Permission */
                    $user->givePermissionTo('Access-Page-Categories');
                    $user->givePermissionTo('Action Edit Categories-Type');
                    $user->givePermissionTo('Action Delete Categories-Type');
                    $user->givePermissionTo('Action Edit Categories-Industry');
                    $user->givePermissionTo('Action Delete Categories-Industry');
                    $user->givePermissionTo('Action Print Categories');
                    $user->givePermissionTo('Action Download Categories');
                    $user->givePermissionTo('Action Create Categories-Type');
                    $user->givePermissionTo('Action Create Categories-Industry');

                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json([
            'admin' => $request->admin,
            'superadmin' => $request->superadmin,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }


    public function change_password(UserRequest $request, $id)
    {
        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
            'new_password' => ['required', Rules\Password::defaults()],
            'retype_new_password' => ['required', Rules\Password::defaults()],
        ]);

        $user = DB::table('users')
            ->select('*')
            ->where('id', $id)
            ->first();

        $success = 0;
        if (Hash::check($request->password, $user->password)) {
            if ($request->new_password == $request->retype_new_password) {

                $user = User::findOrFail($id);
                $user->password = Hash::make($request->new_password);
                $user->save();
                $success = 1;

                $user_change = User::findOrfail($request->id);
                $user_action = User::findOrfail($request->user_id);

                event(new UserLogsEvent($request->user_id, Logs::TYPE_CHANGEPASSWORD_USER, [
                    'email'  =>   $user_action->email,
                    'email_change'  =>   $user_change->email,
                ]));
            } else {
                return response()->json([
                    'errors' => ['new_password' => ["Password does't match."]],
                    'message' => "Password does't match",
                ], 422);
            }
        } else {

            return response()->json([
                'errors' => ['password' => ["Password does't match."]],
                'message' => "Password does't match",
            ], 422);
        }

        return response()->json([
            'success' => $success,
            'user' => $user,
        ]);
    }


    public function reset_password(UserRequest $request, $id)
    {

        $request->validate([
            'reset_password' => ['required', Rules\Password::defaults()],
            'retype_reset_password' => ['required', Rules\Password::defaults()],
        ]);

        $success = 0;
        if ($request->reset_password == $request->retype_reset_password) {
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->reset_password);
            $user->save();
            $success = 1;

            $user_change = User::findOrfail($request->id);
            $user_action = User::findOrfail($request->user_id);

            event(new UserLogsEvent($request->user_id, Logs::TYPE_RESETPASSWORD_USER, [
                'email'  =>   $user_action->email,
                'email_change'  =>   $user_change->email,
            ]));
        } else {
            return response()->json([
                'errors' => ['new_password' => ["Password does't match."]],
                'message' => "Password does't match",
            ], 422);
        }

        return response()->json([
            'success' => $success,
            'user' => $user,
        ]);
    }

    public function exportdata()
    {
        ob_end_clean();
        ob_start();
        return Excel::download(new UsersExport, 'users.xls');
    }

    // delete user method
    public function  delete_user($id, $user_id)
    {

        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);
        $user_for_delete = User::findOrfail($id);
        $success = 0;
        if ($logged_user->hasRole('SUPERADMIN')) {
            $success = 1;
        } else if ($logged_user->hasRole('ADMIN')) {
            if ($user_for_delete->hasRole('SUPERADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is SUPERADMIN"]],
                    'message' => "Cannot delete SUPERADMIN account",
                ], 422);
            } else {
                $success = 1;
            }
        } else {
            if ($user_for_delete->hasRole('SUPERADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is SUPERADMIN"]],
                    'message' => "Cannot delete SUPERADMIN account",
                ], 422);
            } else if ($user_for_delete->hasRole('ADMIN')) {
                return response()->json([
                    'errors' => ['Delete' => ["Account is ADMIN"]],
                    'message' => "Cannot delete ADMIN account",
                ], 422);
            } else {
                $success = 1;
            }
        }

        if ($success == 1) {
            event(new UserLogsEvent($logged_user->id, Logs::TYPE_DELETE_USER, [
                'email'  =>   $logged_user->email,
                'delete_email'  =>   $user_for_delete->email,
            ]));
            $user_for_delete->delete();
        }

        return response()->json([
            'success' => $success,
            'user' =>  $logged_user,
        ]);
    }

    public function  updatePermissions(Request $request)
    {

        $user = User::findOrFail($request->user_id);
        $user->syncPermissions($request->permissions);

        return response()->json([
            'success' => 1,
            'request' => $request->permissions,
            'user_id' => $request->user_id
        ]);
    }

    public function fetch(Request $request)
    {

        $id = Auth::guard('web')->user()->id;

        $user = User::findOrFail(Auth::guard('web')->user()->id);
        $options = $request->options;
        $params = $request->params;
        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = User::query();

        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
        } else if ($user->hasPermissionTo('Action Show-All User')) {
        } else {
            $reqs =  $reqs->where('id', $id);
        }

        $reqs = $reqs->where(function ($query) use ($params) {
            $word = str_replace(" ", "%", $params['searchValue']);
            $query->where([['users.created_at', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.full_name', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.email', 'LIKE', "%" . $word . "%"]])
                ->orWhere([['users.created_at', 'LIKE', "%" . $word . "%"]]);
        })->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $reqs->offset(($options['page'] - 1) * $limit);

        $reqs =  $query->get();
        $count = User::count();

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
        ]);
    }
}
