<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Employers;
use App\Models\Logs;
use App\Models\Document;

use App\Models\WorkforceListing;
use App\Models\WorkforceListing_Tally;

use App\Models\WorkforcePlan_certification;
use App\Models\WorkforcePlan;

use App\Models\User;
use App\Models\Company;
use App\Models\Category;


use App\Events\UserLogsEvent;

class EmployerController extends Controller
{

    public function all_employers(Request $request)
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

        $reqs = $reqs->select('companies.id as company_id', 'employers.id as employer_id', 'categories.id', 'companies.company_name', 'companies.contact_number', 'companies.contact_address', 'employers.verified', 'businesses.industry', 'business_types.type', 'users.full_name', 'users.email')
            ->join('companies', 'companies.id', '=', 'categories.company_id')

            ->join('employers', 'employers.company_id', '=', 'companies.id')
            ->leftJoin('users', 'users.id', '=', 'employers.user_id')
            ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
            ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id');

        $reqs = $reqs->where('companies.id', '!=', 1);

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

        $reqs =  $reqs->take($options['rowsPerPage']);

        if (isset($options['sortBy'])) {
            $reqs  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $reqs = $reqs->offset(($options['page'] - 1) * $limit);

        $reqs = $reqs->get();

        if ($params['filterField'] != "") {

            $count = Category::query();
            $count = $count->join('companies', 'companies.id', '=', 'categories.company_id')
                ->join('employers', 'employers.company_id', '=', 'companies.id')
                ->join('users', 'users.id', '=', 'employers.user_id')
                // ->leftJoin('users', 'users.id', '=', 'employers.user_id')
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id');

            $count  = $count->where('companies.id', '!=', 1);

            if ($params['filterField'] != "") {

                switch ($params['filterField']) {
                    case 'industry':
                        $count =  $count->where('businesses.' . $params['filterField'], $params['filterValue']);
                        break;
                    case 'type':
                        $count =  $count->where('business_types.' . $params['filterField'], $params['filterValue']);
                        break;
                    case 'company_name':
                        $count =  $count->where('companies.' . $params['filterField'], $params['filterValue']);
                        break;
                    case 'full_name':
                        $count =  $count->where('users.' . $params['filterField'], $params['filterValue']);
                        break;
                    case 'email':
                        $count =  $count->where('users.' . $params['filterField'], $params['filterValue']);
                        break;
                }
            }
            $count = $count->count();
        } else {
            $count = Category::query();

            $count = $count->join('companies', 'companies.id', '=', 'categories.company_id')
                ->join('employers', 'employers.company_id', '=', 'companies.id')
                ->join('users', 'users.id', '=', 'employers.user_id')
                // ->leftJoin('users', 'users.company_id', '=', 'companies.id')
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id');

            $count  = $count->where('companies.id', '!=', 1);
            $count = $count->count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function upload(Request $request)
    {

        $filedocument  = [];
        $user = User::where('email', Auth::guard('web')->user()->email)->first();

        if ($request->hasfile('files')) {
            foreach ($request->files as $file) {

                foreach ($file as $key => $value) {

                    $extension = $file[$key]->getClientOriginalExtension();
                    $filenameWithExt = $file[$key]->getClientOriginalName();
                    $extension = $file[$key]->getClientOriginalExtension();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $original_filename = $filename . '.' . $extension;;
                    $time = time();
                    $FileNameToStore = $filename . '_' . $time . '.' . $extension;
                    $newfilename =  $FileNameToStore;
                    $filename = $FileNameToStore;

                    if ($request->type == "Workforce Listing") {
                        $request->file('files')[$key]->storeAs('public/file_list', $FileNameToStore);
                        $file_location = 'storage/file_list/' . $FileNameToStore;
                    } else {
                        $request->file('files')[$key]->storeAs('public/file_plan', $FileNameToStore);
                        $file_location = 'storage/file_plan/' . $FileNameToStore;
                    }

                    $document = Document::create(
                        [
                            'employer_id' => $request->id,
                            'company_id' => $request->company_id,
                            'type' => $request->type,
                            'orig_title' => $original_filename,
                            'title' => $newfilename,
                            'file' => $file_location,
                            'year' => $request->year,
                            'quarter' => $request->quarter,
                            'business_industry_id' =>  $request->industry,
                            'business_type_id' => $request->business_types_id
                        ]
                    );

                    $filedocument = $document;

                    // log start
                    event(new UserLogsEvent($user->id, Logs::TYPE_UPLOAD_EMPLOYER, [
                        'id'  => $user->id,
                        'email' => $user->email,
                        'request_id' => $request->id,
                        'type' => $request->type,
                        'file' => $original_filename,
                        'quarter' => $request->quarter,
                        'year' => $request->year,
                        'doc_id' => $document->id
                    ]));
                    // log end

                }
            }
        }


        if ($request->type == "Workforce Listing") {

            if ($request->listing) {

                foreach ($request->listing as $list) {

                    $data = json_decode($list, true);

                    $name = explode("/", $data['full_name']);
                    // MM/DD/YYYY file
                    // YYYY-MM-DD mysql
                    $startdate = explode("/", $data['employment_start_date']);
                    $enddate = explode("/", $data['employment_end_date']);

                    $fullname =  $name[0] . "," . $name[1] . " " . $name[2];

                    $listing = WorkforceListing::create([
                        'document_id' => $filedocument->id,
                        'company_id' => $request->company_id,
                        'employer_id' =>  $user->id,
                        'full_name' => $fullname,
                        'last_name' => $name[0],
                        'first_name' => $name[1],
                        'middle_name' => $name[2],

                        'major_soc_code' => $data['major_soc_code'],
                        'minor_soc_code' => $data['minor_soc_code'],
                        'position' => $data['position'],
                        'employment_status' => $data['employment_status'],
                        'project_exemption' => $data['project_exemption'],

                        'wage' => $data['wage'],
                        'country_of_citizenship' => $data['country_of_citizenship'],
                        'visa_type_class' => $data['visa_type_class'],
                        'employment_start_date' => $startdate[2] . "-" . $startdate[0] . "-" . $startdate[1],
                        'employment_end_date' => $enddate[2] . "-" . $enddate[0] . "-" . $enddate[1]

                    ]);
                }

                $tally = WorkforceListing_Tally::create([
                    'document_id' => $filedocument->id,
                    'employer_id' => $user->id,
                    'company_id' => $request->company_id,
                    'file_id' => $request->xl_file_id,
                    'fulltime_us_workers' => $request->xl_fulltime_US_workers,
                    'parttime_us_workers' => $request->xl_parttime_US_worker,

                    'fulltime_non_us_workers' => $request->xl_fulltime_nonUS_workers,
                    'parttime_non_us_workers' => $request->xl_parttime_nonUS_workers,
                    'name_and_position' => $request->xl_name_and_position,
                    'year_and_quarter' => $request->xl_year_and_quarter,
                    'company_name' => $request->xl_company_name,

                    'dba' => $request->xl_dba,
                    'day' => $request->xl_listing_day,
                    'month' => $request->xl_listing_month,
                    'year' => $request->xl_listing_year

                ]);
            }
        } else {

            foreach ($request->plan as $list) {

                $data = json_decode($list, true);

                $name = explode("/", $data['full_name']);

                $fullname =  $name[0] . "," . $name[1] . " " . $name[2];

                $plan = WorkforcePlan::create([
                    'document_id' => $filedocument->id,
                    'employer_id' =>  $user->id,
                    'company_id' => $request->company_id,

                    'full_name' => $fullname,
                    'last_name' => $name[0],
                    'first_name' => $name[1],
                    'middle_name' => $name[2],
                    'employment' => $data['employment'],

                    'visa_expiration_date' => $data['visa_expiration_date'],
                    'occupational_classification_code' => $data['occupational_classification_code'],
                    'timetable_replacement_foreignworkers' => $data['timetable_replacement_foreignworkers'],
                    'specific_replacement_plan' => $data['specific_replacement_plan'],
                ]);
            }

            $certification = WorkforcePlan_certification::create([
                'document_id' => $filedocument->id,
                'employer_id' => $user->id,
                'company_id' => $request->company_id,
                'file_id' => $request->xl_file_id,
                'company_name' => $request->xl_company_name,

                'name_and_position' => $request->xl_name_and_position,
                'dba' => $request->xl_dba,
                'year_and_quarter' => $request->xl_year_and_quarter,

                'day' => $request->xl_plan_day,
                'month' => $request->xl_plan_month,
                'year' => $request->xl_plan_year

            ]);
        }

        return response()->json([
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true,
        ]);
    }

    public function show($id)
    {
        $data = Employers::where('user_id', $id)->first();
        return response()->json(
            [
                'data' => $data,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function updateQuarterLock(Request $request, $id)
    {
        $employer = Employers::where('user_id', $id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($employer->checkbox_quarter  != $request->checkbox_quarter) {

            event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $employer->checkbox_quarter,
                'new' => $request->checkbox_quarter,
                'requestid' => $employer->id,
                'field' => 'quarterlock'
            ]));

            $employer->checkbox_quarter = $request->checkbox_quarter;
        }

        if ($employer->quarter  != $request->quarter) {

            event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $employer->quarter,
                'new' => $request->quarter,
                'requestid' => $employer->id,
                'field' => 'quarter'
            ]));


            $employer->quarter = $request->quarter;
        }

        $employer->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function updateYearLock(Request $request, $id)
    {
        $employer = Employers::where('user_id', $id)->first();
        $user = User::findOrfail(Auth::guard('web')->user()->id);

        if ($employer->checkbox_year  != $request->checkbox_year) {

            event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $employer->checkbox_year,
                'new' => $request->checkbox_year,
                'requestid' => $employer->id,
                'field' => 'yearlock'
            ]));

            $employer->checkbox_year = $request->checkbox_year;
        }


        if ($employer->year  != $request->year) {

            event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                'id'  => $user->id,
                'email'  => $user->email,
                'old' =>  $employer->year,
                'new' => $request->year,
                'requestid' => $employer->id,
                'field' => 'year'
            ]));


            $employer->year = $request->year;
        }

        $employer->save();

        return response()->json(
            [
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function update_by_dol(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $employer = Employers::where('user_id', $id)->first();
            $company = Company::where('id', $employer->company_id)->first();
            $user = User::findOrfail(Auth::guard('web')->user()->id);

            if ($employer->company_id  != $request->company_id_new) {

                /** if company is changed employer upload reference must change aswell */

                Document::where('employer_id', $id)
                    ->where('company_id', $company->id)
                    ->update(['company_id' => $request->company_id_new]);

                WorkforcePlan::where('employer_id', $id)
                    ->where('company_id', $company->id)
                    ->update(['company_id' => $request->company_id_new]);

                WorkforcePlan_certification::where('employer_id', $id)
                    ->where('company_id', $company->id)
                    ->update(['company_id' => $request->company_id_new]);

                WorkforceListing::where('employer_id', $id)
                    ->where('company_id', $company->id)
                    ->update(['company_id' => $request->company_id_new]);

                WorkforceListing_Tally::where('employer_id', $id)
                    ->where('company_id', $company->id)
                    ->update(['company_id' => $request->company_id_new]);


                event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                    'id'  => $user->id,
                    'email'  => $user->email,
                    'old' =>  $employer->company_id,
                    'new' => $request->company_id_new,
                    'requestid' => $employer->id,
                    'field' => 'company name'
                ]));

                $employer->company_id = $request->company_id_new;
            }

            if ($employer->contact_address  != $request->contact_address) {

                event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                    'id'  => $user->id,
                    'email'  => $user->email,
                    'old' =>  $employer->contact_address,
                    'new' => $request->contact_address,
                    'requestid' => $employer->id,
                    'field' => 'company_address'
                ]));
                $company->contact_address = $request->contact_address;
                $employer->contact_address = $request->contact_address;
            }

            if ($employer->contact_number  != $request->contact_number) {

                event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                    'id'  => $user->id,
                    'email'  => $user->email,
                    'old' =>  $employer->contact_number,
                    'new' => $request->contact_number,
                    'requestid' => $employer->id,
                    'field' => 'contact number'
                ]));
                $company->contact_number = $request->contact_number;
                $employer->contact_number = $request->contact_number;
            }

            $employer->save();
            $company->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json(
            [
                'name' => $request->company_name,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }

    public function update(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $employer = Employers::where('user_id', $id)->first();
            $company = Company::where('id', $employer->company_id)->first();
            $user = User::findOrfail(Auth::guard('web')->user()->id);

            // if ($employer->company_name  != $request->company_name) {

            // event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
            //     'id'  => $user->id,
            //     'email'  => $user->email,
            //     'old' =>  $employer->company_name,
            //     'new' => $request->company_name,
            //     'requestid' => $employer->id,
            //     'field' => 'company_name'
            // ]));

            // $employer->company_name = $request->company_name;
            // }

            if ($employer->contact_address  != $request->contact_address) {

                event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                    'id'  => $user->id,
                    'email'  => $user->email,
                    'old' =>  $employer->contact_address,
                    'new' => $request->contact_address,
                    'requestid' => $employer->id,
                    'field' => 'company_address'
                ]));
                $company->contact_address = $request->contact_address;
                $employer->contact_address = $request->contact_address;
            }

            if ($employer->contact_number  != $request->contact_number) {

                event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
                    'id'  => $user->id,
                    'email'  => $user->email,
                    'old' =>  $employer->contact_number,
                    'new' => $request->contact_number,
                    'requestid' => $employer->id,
                    'field' => 'contact number'
                ]));
                $company->contact_number = $request->contact_number;
                $employer->contact_number = $request->contact_number;
            }

            // if ($employer->businesses_id  != $request->businesses_id) {

            //     event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
            //         'id'  => $user->id,
            //         'email'  => $user->email,
            //         'old' =>  $employer->businesses_id,
            //         'new' => $request->businesses_id,
            //         'requestid' => $employer->id,
            //         'field' => 'business industry/category'
            //     ]));

            //     $employer->businesses_id = $request->businesses_id;
            // }


            // if ($employer->business_types_id  != $request->business_types_id) {

            //     event(new UserLogsEvent($user->id, Logs::TYPE_UPDATE_EMPLOYER, [
            //         'id'  => $user->id,
            //         'email'  => $user->email,
            //         'old' =>  $employer->business_types_id,
            //         'new' => $request->business_types_id,
            //         'requestid' => $employer->id,
            //         'field' => 'business type'
            //     ]));

            //     $employer->business_types_id = $request->business_types_id;
            // }

            $employer->save();
            $company->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
        }

        DB::commit();

        return response()->json(
            [
                'name' => $request->company_name,
                'success' => true,
                '_benchmark' => microtime(true) -  $this->time_start
            ]
        );
    }
}
