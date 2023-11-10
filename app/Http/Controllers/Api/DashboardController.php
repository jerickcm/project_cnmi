<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


/** Implement Business API */


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Permission;

use App\Models\User;
use App\Models\Company;
use App\Models\Document;

use App\Models\Role_User;
use App\Models\Business;
use App\Models\BusinessType;
use App\Models\Category;
use App\Models\Logs;

class DashboardController extends Controller
{


    public function index()
    {

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        $logout_count = Logs::where('type', 4)->whereNull('deleted_at')->count();
        $login_count = Logs::where('type', 5)->whereNull('deleted_at')->count();

        $company_count = Company::count();

        $employer_count = Role_User::where('role_id', 3)->count();
        $dolestaff_count = Role_User::where('role_id', 4)->count();
        $category_industry_count = Business::count();
        $category_type_count = BusinessType::count();

        $logs_count = Logs::where('user_id', $user->id)->count();
        $user_count = User::query();
        if ($user->DolStaffRole) {
            $user_count = $user_count->count();
            $document_count = Document::count();
            $document_wf_listing_count = Document::where('type', 'Workforce Listing')->count();
            $document_wf_plan_count = Document::where('type', 'Workforce Plan')->count();
        } else {
            $user_count = User::where('id', $user->id)->count();
            $document_count = Document::where('employer_id', $user->id)->count();
            $document_wf_listing_count = Document::where('employer_id', $user->id)->where('type', 'Workforce Listing')->count();
            $document_wf_plan_count = Document::where('employer_id', $user->id)->where('type', 'Workforce Plan')->count();
        }

        return response()->json([
            'user' => $user,
            'data' => [
                'user_count' => $user_count,
                'count' => [$user_count],
                'login_count' =>  $logout_count,
                'logout_count' =>  $login_count,
                'in_vs_out' => [['y' =>   $login_count, 'name' => 'login'], ['y' => $logout_count, 'name' => 'logout']],
                /** */
                'document_count' => $document_count,
                'employer_count' => $employer_count,
                'dolestaff_count' => $dolestaff_count,
                'company_count' => $company_count,
                'logs_count' => [$logs_count],

                'category_industry_count' => $category_industry_count,
                'category_type_count' => $category_type_count,
                'workforce_listing_count' => [$document_wf_listing_count],
                'workforce_plan_count' => [$document_wf_plan_count],

                'document_listing_vs_plan' => [['y' =>   $document_wf_plan_count, 'name' => 'plan'], ['y' =>  $document_wf_listing_count, 'name' => 'listing']],

            ],
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true
        ]);
    }

    public function dashboard_periodic($quarter, $year)
    {

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        $document_count = Document::query();
        $document_wf_listing_count = Document::query();
        $document_wf_plan_count = Document::query();

        $document_wf_listing_count = $document_wf_listing_count->where('type', 'Workforce Listing');
        $document_wf_plan_count = $document_wf_plan_count->where('type', 'Workforce Plan');

        if (!$user->DolStaffRole) {

            $document_count = $document_count->where('employer_id', $user->id);
            $document_wf_listing_count = $document_wf_listing_count->where('employer_id', $user->id);
            $document_wf_plan_count = $document_wf_plan_count->where('employer_id', $user->id);
        }

        if ($quarter == 'All') {

            $document_count = $document_count->where('year', $year)->count();
            $document_wf_listing_count = $document_wf_listing_count->where('year', $year)->count();
            $document_wf_plan_count = $document_wf_plan_count->where('year', $year)->count();
        } else if ($year == "All") {

            $document_count = $document_count->count();
            $document_wf_listing_count = $document_wf_listing_count->where('quarter', $quarter)->count();
            $document_wf_plan_count = $document_wf_plan_count->where('quarter', $quarter)->count();
        } else if ($year == "All" && $quarter == "All") {

            $document_count = $document_count::count();
            $document_wf_listing_count = $document_wf_listing_count->count();
            $document_wf_plan_count = $document_wf_plan_count->count();
        } else {

            $document_count = $document_count->where('year', $year)
                ->where('quarter', $quarter)
                ->count();

            $document_wf_listing_count = $document_wf_listing_count->where('year', $year)
                ->where('quarter', $quarter)
                ->count();

            $document_wf_plan_count = $document_wf_plan_count->where('year', $year)
                ->where('quarter', $quarter)
                ->count();
        }

        return response()->json([
            'data' => [
                'document_count' => [$document_count],
                'document_listing_vs_plan' => [['y' =>   $document_wf_plan_count, 'name' => 'plan'], ['y' =>  $document_wf_listing_count, 'name' => 'listing']],
                'list' => [$document_wf_listing_count],
                'plan' => [$document_wf_plan_count]
            ],
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true
        ]);
    }

    public function business()
    {
        $business = Business::select('id', 'industry as value')->orderBy('id', 'asc')->get();
        $type = BusinessType::select('id', 'type as value', DB::raw('CAST(business_id AS UNSIGNED) AS parent'))->orderBy('id', 'asc')->get();

        return response()->json([
            'hosting' => config('custom.url'),
            'business' => $business,
            'type' => $type,
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true
        ]);
    }
    public function host()
    {

        return response()->json([
            'hosting' => config('custom.url'),
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true
        ]);
    }
    public function permission()
    {
        $allPermissions = Permission::pluck('name');

        return response()->json([
            'allPermission' => $allPermissions,
            '_benchmark' => microtime(true) -  $this->time_start,
            'success' => true
        ]);
    }
}
