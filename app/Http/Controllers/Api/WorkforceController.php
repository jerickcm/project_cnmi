<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WorkforceListing;
use App\Models\WorkforceListing_Tally;

use App\Models\WorkforcePlan_certification;
use App\Models\WorkforcePlan;

use App\Models\Logs;
use App\Models\User;
use App\Models\Document;
use App\Events\UserLogsEvent;
use Illuminate\Support\Facades\Auth;

use App\Exports\WFPlan_data;
use App\Exports\WFPlan_tally;
use App\Exports\WFListing_data;
use App\Exports\WFListing_tally;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkforceController extends Controller
{

    public function fetch_workforce_plan(Request $request, $document_id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforcePlan::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->where('document_id', $document_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        if ($params['filterField'] != "") {
            $count = WorkforcePlan::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = WorkforcePlan::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }


    public function fetch_workforce_listing(Request $request, $document_id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforceListing::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }
        $reqs  = $reqs->where('document_id', $document_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        if ($params['filterField'] != "") {
            $count = WorkforceListing::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = WorkforceListing::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function fetch_workforce_plan_tally(Request $request, $document_id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforcePlan_certification::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->where('document_id', $document_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        if ($params['filterField'] != "") {
            $count = WorkforcePlan_certification::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = WorkforcePlan_certification::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }


    public function fetch_workforce_listing_tally(Request $request, $document_id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforceListing_Tally::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }
        $reqs  = $reqs->where('document_id', $document_id);


        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        if ($params['filterField'] != "") {
            $count = WorkforceListing_Tally::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = WorkforceListing_Tally::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function show_plan($workforce_id)
    {
        $data = WorkforcePlan::where('id', $workforce_id)->first();

        return response()->json(
            [
                'data' => $data == null ? [] : $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function show_plan_tally($workforce_id)
    {
        $data = WorkforcePlan_certification::where('id', $workforce_id)->first();

        return response()->json(
            [
                'data' => $data == null ? [] : $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function show_list($workforce_id)
    {
        $data = WorkforceListing::where('id', $workforce_id)->first();

        return response()->json(
            [
                'data' => $data == null ? [] : $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function show_list_tally($workforce_id)
    {
        $data = WorkforceListing_Tally::where('id', $workforce_id)->first();
        return response()->json(
            [
                'data' => $data == null ? [] : $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function update_plan(Request $request, $workforce_id)
    {
        $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name;

        $data = WorkforcePlan::where('id', $workforce_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($data->fullname  != $fullname) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->fullname,
                'new' => $fullname,
                'requestid' => $request->id,
                'field' => 'full name'
            ]));

            $data->full_name = $fullname;
        }

        if ($data->first_name  != $request->first_name) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->first_name,
                'new' => $request->first_name,
                'requestid' => $request->id,
                'field' => 'first name'
            ]));

            $data->first_name = $request->first_name;
        }


        if ($data->middle_name  != $request->middle_name) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->middle_name,
                'new' => $request->middle_name,
                'requestid' => $request->id,
                'field' => 'middle name'
            ]));

            $data->middle_name = $request->middle_name;
        }


        if ($data->last_name  != $request->last_name) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->last_name,
                'new' => $request->last_name,
                'requestid' => $request->id,
                'field' => 'last name'
            ]));

            $data->last_name = $request->last_name;
        }

        if ($data->employment  != $request->employment) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->employment,
                'new' => $request->employment,
                'requestid' => $request->id,
                'field' => 'employment'
            ]));

            $data->employment = $request->employment;
        }

        if ($data->visa_expiration_date  != $request->visa_expiration_date) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->visa_expiration_date,
                'new' => $request->visa_expiration_date,
                'requestid' => $request->id,
                'field' => 'visa expiration date'
            ]));

            $data->visa_expiration_date = $request->visa_expiration_date;
        }


        if ($data->occupational_classification_code  != $request->occupational_classification_code) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->occupational_classification_code,
                'new' => $request->occupational_classification_code,
                'requestid' => $request->id,
                'field' => 'occupation classification code'
            ]));

            $data->occupational_classification_code = $request->occupational_classification_code;
        }

        if ($data->timetable_replacement_foreignworkers  != $request->timetable_replacement_foreignworkers) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->timetable_replacement_foreignworkers,
                'new' => $request->timetable_replacement_foreignworkers,
                'requestid' => $request->id,
                'field' => 'timetable_replacement_foreignworkers'
            ]));

            $data->timetable_replacement_foreignworkers = $request->timetable_replacement_foreignworkers;
        }

        if ($data->specific_replacement_plan  != $request->specific_replacement_plan) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->specific_replacement_plan,
                'new' => $request->specific_replacement_plan,
                'requestid' => $request->id,
                'field' => 'specific_replacement_plan'
            ]));

            $data->specific_replacement_plan = $request->specific_replacement_plan;
        }

        $data->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function update_plan_tally(Request $request, $workforce_id)
    {

        $data = WorkforcePlan_certification::where('id', $workforce_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($data->file_id  != $request->file_id) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->file_id,
                'new' => $request->file_id,
                'requestid' => $request->id,
                'field' => 'File ID'
            ]));

            $data->file_id = $request->file_id;
        }

        if ($data->name_and_position  != $request->name_and_position) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->name_and_position,
                'new' => $request->name_and_position,
                'requestid' => $request->id,
                'field' => 'Name and Position'
            ]));

            $data->name_and_position = $request->name_and_position;
        }

        if ($data->company_name  != $request->company_name) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->company_name,
                'new' => $request->company_name,
                'requestid' => $request->id,
                'field' => 'Company Name'
            ]));

            $data->company_name = $request->company_name;
        }

        if ($data->dba  != $request->dba) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->dba,
                'new' => $request->dba,
                'requestid' => $request->id,
                'field' => 'Company Name'
            ]));

            $data->dba = $request->dba;
        }

        if ($data->day  != $request->day) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->day,
                'new' => $request->day,
                'requestid' => $request->id,
                'field' => 'Company Name'
            ]));

            $data->day = $request->day;
        }

        if ($data->month  != $request->month) {

            event(new UserLogsEvent($user->id, Logs::TYPE_WFPLAN_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->month,
                'new' => $request->month,
                'requestid' => $request->id,
                'field' => 'Month'
            ]));

            $data->month = $request->month;
        }

        if ($data->year  != $request->year) {



            $data->year = $request->year;
        }

        $data->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }


    public function update_list(Request $request, $workforce_id)
    {

        $fullname = $request->first_name . " " . $request->middle_name . " " . $request->last_name;
        $data = WorkforceListing::where('id', $workforce_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($data->fullname  != $fullname) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->fullname,
                'new' =>  $fullname,
                'requestid' => $request->id,
                'field' => 'Full Name'
            ]));
            $data->full_name = $request->full_name;
        }

        if ($data->first_name  != $request->first_name) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->first_name,
                'new' =>  $request->first_name,
                'requestid' => $request->id,
                'field' => 'First Name'
            ]));
            $data->first_name = $request->first_name;
        }

        if ($data->middle_name  != $request->middle_name) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->middle_name,
                'new' =>  $request->middle_name,
                'requestid' => $request->id,
                'field' => 'Middle Name'
            ]));
            $data->middle_name = $request->middle_name;
        }

        if ($data->last_name  != $request->last_name) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->last_name,
                'new' =>  $request->last_name,
                'requestid' => $request->id,
                'field' => 'Last Name'
            ]));
            $data->last_name = $request->last_name;
        }

        if ($data->major_soc_code  != $request->major_soc_code) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->major_soc_code,
                'new' =>  $request->major_soc_code,
                'requestid' => $request->id,
                'field' => 'Major Soc Code'
            ]));
            $data->major_soc_code = $request->major_soc_code;
        }

        if ($data->minor_soc_code  != $request->minor_soc_code) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->minor_soc_code,
                'new' =>  $request->minor_soc_code,
                'requestid' => $request->id,
                'field' => 'Minor Soc Code'
            ]));
            $data->minor_soc_code = $request->minor_soc_code;
        }

        if ($data->position  != $request->position) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->position,
                'new' =>  $request->position,
                'requestid' => $request->id,
                'field' => 'Minor Soc Code'
            ]));
            $data->position = $request->position;
        }

        if ($data->project_exemption  != $request->project_exemption) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->project_exemption,
                'new' =>  $request->project_exemption,
                'requestid' => $request->id,
                'field' => 'Minor Soc Code'
            ]));
            $data->project_exemption = $request->project_exemption;
        }

        if ($data->employment_status  != $request->employment_status) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->employment_status,
                'new' =>  $request->employment_status,
                'requestid' => $request->id,
                'field' => 'Minor Soc Code'
            ]));
            $data->employment_status = $request->employment_status;
        }


        if ($data->wage  != $request->wage) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->wage,
                'new' =>  $request->wage,
                'requestid' => $request->id,
                'field' => 'Wage'
            ]));
            $data->wage = $request->wage;
        }

        if ($data->country_of_citizenship  != $request->country_of_citizenship) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->country_of_citizenship,
                'new' =>  $request->country_of_citizenship,
                'requestid' => $request->id,
                'field' => 'country_of_citizenship'
            ]));
            $data->country_of_citizenship = $request->country_of_citizenship;
        }

        if ($data->visa_type_class  != $request->visa_type_class) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->visa_type_class,
                'new' =>  $request->visa_type_class,
                'requestid' => $request->id,
                'field' => 'visa_type_class'
            ]));
            $data->visa_type_class = $request->visa_type_class;
        }

        if ($data->employment_start_date  != $request->employment_start_date) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->employment_start_date,
                'new' =>  $request->employment_start_date,
                'requestid' => $request->id,
                'field' => 'employment_start_date'
            ]));
            $data->employment_start_date = $request->employment_start_date;
        }

        if ($data->employment_end_date  != $request->employment_end_date) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->employment_end_date,
                'new' =>  $request->employment_end_date,
                'requestid' => $request->id,
                'field' => 'employment_end_date'
            ]));
            $data->employment_end_date = $request->employment_end_date;
        }

        $data->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }


    public function update_list_tally(Request $request, $workforce_id)
    {

        $data = WorkforceListing_Tally::where('id', $workforce_id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);



        if ($data->fulltime_us_workers  != $request->fulltime_us_workers) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->fulltime_us_workers,
                'new' =>  $request->fulltime_us_workers,
                'requestid' => $request->id,
                'field' => 'Full Time US Workers'
            ]));
            $data->fulltime_us_workers = $request->fulltime_us_workers;
        }

        if ($data->parttime_us_workers  != $request->parttime_us_workers) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->parttime_us_workers,
                'new' =>  $request->parttime_us_workers,
                'requestid' => $request->id,
                'field' => 'Parttime US Workers'
            ]));
            $data->parttime_us_workers = $request->parttime_us_workers;
        }

        if ($data->fulltime_non_us_workers  != $request->fulltime_non_us_workers) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->fulltime_non_us_workers,
                'new' =>  $request->fulltime_non_us_workers,
                'requestid' => $request->id,
                'field' => 'fulltime_non_us_workers'
            ]));
            $data->fulltime_non_us_workers = $request->fulltime_non_us_workers;
        }

        if ($data->parttime_non_us_workers  != $request->parttime_non_us_workers) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->parttime_non_us_workers,
                'new' =>  $request->parttime_non_us_workers,
                'requestid' => $request->id,
                'field' => 'parttime_non_us_workers'
            ]));
            $data->parttime_non_us_workers = $request->parttime_non_us_workers;
        }


        if ($data->name_and_position  != $request->name_and_position) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->name_and_position,
                'new' =>  $request->name_and_position,
                'requestid' => $request->id,
                'field' => 'name_and_position'
            ]));
            $data->name_and_position = $request->name_and_position;
        }

        if ($data->year_and_quarter  != $request->year_and_quarter) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->year_and_quarter,
                'new' =>  $request->year_and_quarter,
                'requestid' => $request->id,
                'field' => 'year_and_quarter'
            ]));
            $data->year_and_quarter = $request->year_and_quarter;
        }

        if ($data->company_name  != $request->company_name) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->company_name,
                'new' =>  $request->company_name,
                'requestid' => $request->id,
                'field' => 'company_name'
            ]));
            $data->company_name = $request->company_name;
        }

        if ($data->dba  != $request->dba) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->dba,
                'new' =>  $request->dba,
                'requestid' => $request->id,
                'field' => 'dba'
            ]));
            $data->dba = $request->dba;
        }

        if ($data->day  != $request->day) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->day,
                'new' =>  $request->day,
                'requestid' => $request->id,
                'field' => 'day'
            ]));
            $data->day = $request->day;
        }


        if ($data->month  != $request->month) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->month,
                'new' =>  $request->month,
                'requestid' => $request->id,
                'field' => 'month'
            ]));
            $data->month = $request->month;
        }

        if ($data->year  != $request->year) {
            event(new UserLogsEvent($user->id, Logs::TYPE_WFLIST_TALLY_EDIT, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $data->year,
                'new' =>  $request->year,
                'requestid' => $request->id,
                'field' => 'year'
            ]));
            $data->year = $request->year;
        }

        $data->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function export_plan_data(Request $request, $workforce_id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforcePlan::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->where('document_id', $workforce_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        return (new WFPlan_data($reqs))->download('export.xls');
    }

    public function export_plan_tally(Request $request, $document_id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforcePlan_certification::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->where('document_id', $document_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        return (new WFPlan_tally($reqs))->download('export.xls');
    }

    public function export_listing_data(Request $request, $document_id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforceListing::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }
        $reqs  = $reqs->where('document_id', $document_id);


        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        return (new WFListing_data($reqs))->download('export.xls');
    }

    public function export_listing_tally(Request $request, $document_id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = WorkforceListing_Tally::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }
        $reqs  = $reqs->where('document_id', $document_id);

        if ($params['searchField'] == "") {
        } else {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([[$params['searchField'], 'LIKE', "%" . $word . "%"]]);
            });
        }

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);
        $reqs = $reqs->get();

        return (new WFListing_tally($reqs))->download('export.xls');
    }


    /** Destroy Methods */

    /**1 */

    public function destroy_list(Request $request, $id)
    {
        $data = WorkforceListing::findOrfail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    /**2 */

    public function destroy_listtally(Request $request, $id)
    {
        $data = WorkforceListing_Tally::findOrfail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    /**3 */

    public function destroy_plan(Request $request, $id)
    {
        $data = WorkforcePlan::findOrfail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    /**4 */

    public function destroy_plantally(Request $request, $id)
    {
        $data = WorkforcePlan_certification::findOrfail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    /**5 */

    public function destroy_wf_listing(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $data = Document::findOrfail($id);
            $data->wflist()->delete();
            $data->wflisttally()->delete();
            $data->delete();

            Storage::disk('public')->move('/file_list/' . $data->title, '/trash_file_listing/' . $data->title);
     
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

    /**6 */

    public function destroy_wf_plan(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $data = Document::findOrfail($id);
            $data->wfplan()->delete();
            $data->wfplantally()->delete();
            $data->delete();

            Storage::disk('public')->move('/file_plan/' . $data->title, '/trash_file_plan/' . $data->title);
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
}
