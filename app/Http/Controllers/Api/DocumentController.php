<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App\Models\User;
use App\Models\Document;

use App\Events\UserLogsEvent;
use App\Exports\WFPlanExport;
use App\Exports\WFListingExport;

class DocumentController extends Controller
{

    public function destroy(Request $request, $id)
    {
        $respondent = Document::findOrfail($id);
        $respondent->delete();

        return response()->json([
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function fetch(Request $request, $company_id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Document::query();

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        $reqs  = $reqs->select('businesses.industry', 'documents.*', 'business_types.type as businesstype', 'employers.company_name')
            ->join('employers', 'employers.id', '=', 'documents.employer_id')
            ->leftJoin('businesses', 'businesses.id', '=', 'documents.business_industry_id')
            ->leftJoin('business_types', 'business_types.id', '=', 'documents.business_type_id');

        if ($params['select'] == "All") {
        } else {
            $reqs  = $reqs->where("documents.company_id",   $company_id);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['employer_id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['orig_title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['file', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.notes', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.year', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.quarter', 'LIKE', "%" . $word . "%"]])

                    ->orwhere([['business_types.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word . "%"]]);
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

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if ($params['filterField'] != "") {
            $count = Document::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = Document::count();
        }

        return response()->json([
            'id' => $user->id,
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function fetch_plan(Request $request, $id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Document::query();
        $reqs  = $reqs->where("documents.type", "Workforce Plan");

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->select('businesses.industry', 'documents.*', 'business_types.type as businesstype', 'employers.company_name')
            ->join('employers', 'employers.id', '=', 'documents.employer_id')
            ->join('businesses', 'businesses.id', '=', 'documents.business_industry_id')
            ->join('business_types', 'business_types.id', '=', 'documents.business_type_id');

        if ($params['select'] == "All") {
        } else {

            $reqs  = $reqs->where("employer_id", $id);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['employer_id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['orig_title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['file', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.notes', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.year', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.quarter', 'LIKE', "%" . $word . "%"]])

                    ->orwhere([['business_types.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word . "%"]]);
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

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if ($params['filterField'] != "") {
            $count = Document::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = Document::count();
        }

        return response()->json([
            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    /** Fetch Plan Export */
    public function export_fetch_plan(Request $request, $id)
    {

        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Document::query();
        $reqs  = $reqs->where("documents.type", "Workforce Plan");

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->select('businesses.industry', 'documents.*', 'business_types.type as businesstype', 'employers.company_name')
            ->join('employers', 'employers.id', '=', 'documents.employer_id')
            ->join('businesses', 'businesses.id', '=', 'documents.business_industry_id')
            ->join('business_types', 'business_types.id', '=', 'documents.business_type_id');

        if ($params['select'] == "All") {
        } else {

            $reqs  = $reqs->where("employer_id", $id);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['employer_id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['orig_title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['file', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.notes', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.year', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.quarter', 'LIKE', "%" . $word . "%"]])

                    ->orwhere([['business_types.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word . "%"]]);
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

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        return (new WFPlanExport($reqs))->download('export.xls');
        // return (new DolByCategoryExport($reqs))->download('export.xls');
    }

    /** Fetch Listing Export */
    public function export_fetch_listing(Request $request, $id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Document::query();
        $reqs  = $reqs->where("documents.type", "Workforce Listing");

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->select('businesses.industry', 'documents.*', 'business_types.type as businesstype', 'employers.company_name')
            ->join('employers', 'employers.id', '=', 'documents.employer_id')
            ->join('businesses', 'businesses.id', '=', 'documents.business_industry_id')
            ->join('business_types', 'business_types.id', '=', 'documents.business_type_id');

        if ($params['select'] == "All") {
        } else {

            $reqs  = $reqs->where("employer_id", $id);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['employer_id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['orig_title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['file', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.notes', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.year', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.quarter', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['business_types.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word . "%"]]);
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

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        return (new WFListingExport($reqs))->download('export.xls');
    }

    public function fetch_listing(Request $request, $id)
    {
        $options = $request->options;
        $params = $request->params;

        $limit =  $options['rowsPerPage'] ? $options['rowsPerPage'] : 10;

        $reqs = Document::query();
        $reqs  = $reqs->where("documents.type", "Workforce Listing");

        if ($params['filterField'] != "") {
            $reqs =  $reqs->where($params['filterField'], $params['filterValue']);
        }

        $reqs  = $reqs->select('businesses.industry', 'documents.*', 'business_types.type as businesstype', 'employers.company_name')
            ->join('employers', 'employers.id', '=', 'documents.employer_id')
            ->join('businesses', 'businesses.id', '=', 'documents.business_industry_id')
            ->join('business_types', 'business_types.id', '=', 'documents.business_type_id');

        if ($params['select'] == "All") {
        } else {

            $reqs  = $reqs->where("employer_id", $id);
        }

        if ($params['searchField'] == "") {
            $reqs = $reqs->where(function ($query) use ($params) {
                $word = str_replace(" ", "%", $params['searchValue']);
                $query->where([['employer_id', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['orig_title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['title', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['file', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.notes', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.year', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['documents.quarter', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['business_types.type', 'LIKE', "%" . $word . "%"]])
                    ->orwhere([['businesses.industry', 'LIKE', "%" . $word . "%"]]);
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

        if (isset($options['sortBy'])) {
            $query  = $reqs->orderBy($options['sortBy'],  strtoupper($options['sortType']));
        }

        $query = $query->offset(($options['page'] - 1) * $limit);
        $reqs =  $query->get();

        if ($params['filterField'] != "") {
            $count = Document::query();
            $count = $count->where($params['filterField'], $params['filterValue']);
            $count = $count->count();
        } else {
            $count = Document::count();
        }

        return response()->json([

            'data' => $reqs,
            'totalRecords' => $count,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }
}
