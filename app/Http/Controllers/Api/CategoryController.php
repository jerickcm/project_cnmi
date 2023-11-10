<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Exports\DolByCategoryExport;

use App\Models\Category;
use App\Models\Business;
use App\Models\BusinessType;
use App\Models\Logs;

use App\Models\User;
use App\Events\UserLogsEvent;
use App\Models\Company;

class CategoryController extends Controller
{
    public function get_industry_by_name($name)
    {
        $data  = Business::select('businesses.*')->where('industry', $name)->first();
        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function show_with_type(Request $request, $id)
    {

        $data  = Business::select('businesses.*')->where('id', $id)->with('b_type')->first();
        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function export(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Category::query();

        if ($params['filterField'] != "") {

            switch ($params['filterField']) {
                case 'industry':
                    $reqs = $reqs->where('businesses.' . $params['filterField'], $params['filterValue']);
                    break;
                case 'type':
                    $reqs = $reqs->where('business_types.' . $params['filterField'], $params['filterValue']);
                    break;
                case 'company_name':
                    $reqs = $reqs->where('companies.' . $params['filterField'], $params['filterValue']);
                    break;
                case 'full_name':
                    $reqs = $reqs->where('users.' . $params['filterField'], $params['filterValue']);
                    break;
                case 'email':
                    $reqs = $reqs->where('users.' . $params['filterField'], $params['filterValue']);
                    break;
            }
        }

        $reqs = $reqs->select('companies.id as company_id', 'employers.id as employer_id', 'categories.id', 'companies.company_name', 'companies.contact_number', 'companies.contact_address', 'employers.verified', 'businesses.industry', 'business_types.type', 'users.full_name')
            ->join('companies', 'companies.id', '=', 'categories.company_id')
            ->leftJoin('employers', 'employers.company_id', '=', 'companies.id')
            ->leftJoin('users', 'users.company_id', '=', 'companies.id')
            ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
            ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id');

        if ($params['searchField'] == "") {

            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['categories.id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['companies.company_name', 'LIKE', "%" . $word  . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word  . "%"]])
                    ->orwhere([['business_types.type', 'LIKE', "%" . $word  . "%"]])
                    ->orwhere([['users.email', 'LIKE', "%" . $word  . "%"]])
                    ->orwhere([['users.full_name', 'LIKE', "%" . $word  . "%"]]);
            });
        } else {

            $reqs = $reqs->where(function ($query) use ($params) {

                $word = str_replace(" ", "%", $params['searchValue']);

                switch ($params['searchField']) {
                    case "type":
                        $query->where([['business_types.' . $params['searchField'], 'LIKE', "%" . $word . "%"]]);
                        break;
                    case "industry":
                        $query->where([['businesses.' . $params['searchField'], 'LIKE', "%" . $word . "%"]]);
                        break;
                    case "company_name":
                        $query->where([['companies.' . $params['searchField'], 'LIKE', "%" . $word . "%"]]);
                        break;
                    case "full_name":
                        $query->where([['users.' . $params['searchField'], 'LIKE', "%" . $word . "%"]]);
                        break;
                    case "email":
                        $query->where([['users.' . $params['searchField'], 'LIKE', "%" . $word . "%"]]);
                        break;
                    default:
                        $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
                }
            });
        }

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->get();

        return (new DolByCategoryExport($reqs))->download('export.xls');
    }


    public function show($company_id)
    {
        $data = Category::where('company_id', $company_id)->get();
        return response()->json(
            [
                'data' => $data == null ? [] : $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function update(Request $request, $company_id)
    {

        DB::beginTransaction();

        try {

            Category::where('company_id', $request->company_id)->delete();

            $user = User::findOrfail(Auth::guard('web')->user()->id);
            $company = Company::findOrfail($company_id);

            foreach ($request->industry as $key => $value) {

                if (($value === null || empty($value))) {
                } else if ($request->type[$key] == null || empty($request->type[$key])) {
                } else {
                    $category = Category::create([
                        'company_id' => $company_id,
                        'business_id' => $value,
                        'business_type_id' => $request->type[$key],
                    ]);

                    event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_CATEGORY, [
                        'id'  => $user->id,
                        'email' => $user->email,
                        'created_category_id' =>  $category->id,
                        'company_id' =>  $company_id,
                        'company_name' => $company->company_name,
                    ]));
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();


        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function fetch_business_type(Request $request, $id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = BusinessType::query();
        $reqs  = $reqs->where('business_id', $id);

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['id', 'LIKE', "%" . $word . "%"]]);
            })->take($options['rowsPerPage']);
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            })->take($options['rowsPerPage']);
        }

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if ($params['filterField'] != "") {
            $count = BusinessType::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count  = $count->where('business_id', $id);
            $count = $count->count();
        } else {
            $count = BusinessType::where('business_id', $id)->count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function fetch_business_categories(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Business::query();

        $reqs  = $reqs->select('businesses.*')->with('b_type');

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['industry', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['id', 'LIKE', "%" . $word . "%"]]);
            })->take($options['rowsPerPage']);
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            })->take($options['rowsPerPage']);
        }

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if ($params['filterField'] != "") {
            $count = Business::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = Business::count();
        }

        return response()->json([

            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function update_business_industry(Request $request)
    {
        $business = Business::where('id', $request->id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($business->industry != $request->industry) {

            event(new UserLogsEvent($user->id, Logs::TYPE_BUSINESS_INDUSTRY_EDIT, [
                'id'  => $user->id,
                'email' => $user->email,
                'old' =>   $business->industry,
                'new' =>   $request->industry,
                'field' => 'business industry',
                'edit_id' =>  $request->id
            ]));

            $business->industry = $request->industry;
            $business->save();
        }

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function update_business_type(Request $request)
    {

        $business_type = BusinessType::where('id', $request->id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($business_type->type != $request->type) {

            event(new UserLogsEvent($user->id, Logs::TYPE_BUSINESS_TYPE_EDIT, [
                'id'  => $user->id,
                'email' => $user->email,
                'old' =>   $business_type->type,
                'new' =>   $request->type,
                'field' => 'business type',
                'edit_id' =>  $request->id
            ]));

            $business_type->type = $request->type;
            $business_type->save();
        }

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function destroy_business_type(Request $request, $id)
    {

        $business_type = BusinessType::where('id', $id)->first();
        $business_type->delete();


        $user = User::findOrfail(Auth::guard('web')->user()->id);
        event(new UserLogsEvent($user->id, Logs::TYPE_BUSINESSTYPE_DELETE, [
            'id'  => $user->id,
            'email' => $user->email,
            'business_id' =>   $business_type->id,
            'business_type' => $business_type->type,
        ]));

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function destroy(Request $request, $id)
    {


        $business = Category::findOrfail($id);
        $business->delete();

        $business_type = BusinessType::where('business_id', $id)->get();
        $business_type->delete();

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function destroy_business(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $business = Business::findOrfail($id);
            $business->b_type()->delete();
            $business->delete();

            $user = User::findOrfail(Auth::guard('web')->user()->id);
            event(new UserLogsEvent($user->id, Logs::TYPE_BUSINESSINDUSTRY_DELETE, [
                'id'  => $user->id,
                'email' => $user->email,
                'business_id' =>   $business->id,
                'business_industry' => $business->industry,
            ]));
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function store_businesstype(Request $request)
    {

        $business_type = BusinessType::create([
            'business_id' =>  $request->business_id,
            'type' =>  $request->type,
        ]);

        $business = Business::findorfail($request->business_id);

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        event(new UserLogsEvent($user->id, Logs::TYPE_CREATE_CATEGORY_BUSINESS_TYPE, [
            'id'  => $user->id,
            'email' => $user->email,
            'create_id' =>  $business_type->id,
            'business_type' =>  $request->type,
            'business_industry_id' => $request->business_id,
            'business_industry' => $business->industry,
        ]));

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function store_businessindustry(Request $request)
    {

        $business = Business::create([
            'industry' =>  $request->industry,
        ]);

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        event(new UserLogsEvent($user->id, Logs::TYPE_CREATE_CATEGORY_BUSINESS_INDUSTRY, [
            'id'  => $user->id,
            'email' => $user->email,
            'create_id' =>  $business->id,
            'business_industry' => $business->industry,
        ]));

        return response()->json([
            'data' =>  $business,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }
}
