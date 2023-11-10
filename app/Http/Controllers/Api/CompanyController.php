<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Events\UserLogsEvent;

use App\Models\Company;
use App\Models\User;
use App\Models\Logs;

use App\Exports\CompaniesExport;

class CompanyController extends Controller
{
    public function export_companies(Request $request)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Company::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['id', 'LIKE', "%" . $word . "%"]]);
            });
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs =  $query->get();


        return (new CompaniesExport($reqs))->download('export.xls');
    }

    public function  show_company($name)
    {
        $data = Company::where('company_name', $name)->first();
        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function show($id)
    {
        $data = Company::where('id', $id)->first();
        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function store(Request $request)
    {

        Company::create([
            'company_name' => $request->company_name,
            'contact_number' => $request->contact_number,
            'contact_address' => $request->contact_address,
        ]);

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function getSearchfield(Request $request)
    {
        $data =  Company::select('id', $request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function fetch_companies(Request $request)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Company::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['company_name', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['contact_number', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['contact_address', 'LIKE', "%" . $word . "%"]]);
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
            $count = Company::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = Company::count();
        }

        return response()->json([

            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }


    public function getSearchfield_validonly(Request $request)
    {
        $data =  Company::select('id', $request->field)->groupBy($request->field)
            ->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])
            ->where('id', "!=", 1)
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function destroy(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $company = Company::findOrfail($id);
            $company->categorylink()->delete();
            $company->delete();

            $user = User::findOrfail(Auth::guard('web')->user()->id);
            event(new UserLogsEvent($user->id, Logs::TYPE_COMPANY_DELETE, [
                'id'  => $user->id,
                'email' => $user->email,
                'company_id' =>   $company->id,
                'company_name' => $company->company_name,
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

    public function update(Request $request, $id)
    {
        $company = Company::findOrfail($id);
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($company->company_name != $request->company_name) {

            event(new UserLogsEvent($user->id, Logs::TYPE_COMPANY_EDIT, [
                'id'  => $user->id,
                'email' => $user->email,
                'company_id' =>   $company->id,
                'old' => $company->company_name,
                'new' => $request->company_name,
                'field' => 'company name'
            ]));

            $company->company_name  = $request->company_name;
        }

        if ($company->contact_number != $request->contact_number) {

            event(new UserLogsEvent($user->id, Logs::TYPE_COMPANY_EDIT, [
                'id'  => $user->id,
                'email' => $user->email,
                'company_id' =>   $company->id,
                'old' => $company->contact_number,
                'new' => $request->contact_number,
                'field' => 'contact number'
            ]));

            $company->contact_number = $request->contact_number;
        }

        if ($company->contact_address != $request->contact_address) {

            event(new UserLogsEvent($user->id, Logs::TYPE_COMPANY_EDIT, [
                'id'  => $user->id,
                'email' => $user->email,
                'company_id' =>   $company->id,
                'old' => $company->contact_address,
                'new' => $request->contact_address,
                'field' => 'contact address'
            ]));

            $company->contact_address = $request->contact_address;
        }

        $company->save();

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }
}
