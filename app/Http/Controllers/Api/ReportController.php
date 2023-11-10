<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Company;
use App\Models\Category;

use App\Models\Employers;
use App\Models\WorkforceListing;
use App\Models\WorkforceListing_Tally;

use App\Models\WorkforcePlan_certification;
use App\Models\WorkforcePlan;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

use App\Exports\ReportCompanyExport;
use App\Exports\ReportEmployersExport;
use App\Exports\ReportWorkforcePlanExport;
use App\Exports\ReportWorkforcePlanTallyExport;
use App\Exports\ReportWorkforceListExport;
use App\Exports\ReportWorkforceListTallyExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $req = $request->data;

        $one_company = 0;

        if (isset($req['company_id'])) {

            /** check company data */
            $one_company = 1;

            $company_id = $req['company_id']['value'];

            $company = Company::where('id', $company_id)->first();

            $category = Category::select('businesses.industry', 'business_types.type')
                ->where('categories.company_id', $req['company_id']['value'])
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id')
                ->get();


            $employer = Employers::select('users.email', 'users.full_name', 'companies.company_name')
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 2)
                ->where('users.company_id', '!=', 1)
                ->where('users.company_id', '=', $company_id)
                ->join('users', 'users.id', '=', 'employers.user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->get();

            //add company employee user

            $workforce_listing = WorkforceListing::query();
            $workforce_plan = WorkforcePlan::query();
            $workforce_plan_tally = WorkforcePlan_certification::query();
            $workforce_list_tally = WorkforceListing_Tally::query();


            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;
                case isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;
                case isset($req['year']) && isset($req['year_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                case isset($req['year']):

                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();

                    break;

                case isset($req['quarter']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['year']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                default:
                    $workforce_listing =  $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;
            }

            $data = [
                'company' => $company,
                'category' => $category,
                'employer' => $employer,
                'wf_plan' =>  $workforce_plan == null ? [] : $workforce_plan,
                'wf_list' => $workforce_listing == null ? [] : $workforce_listing,

                'wf_list_tally' => $workforce_list_tally == null ? [] : $workforce_list_tally,
                'wf_plan_tally' => $workforce_plan_tally == null ? [] : $workforce_plan_tally,
            ];

            $pdf = PDF::loadView('report', $data)->setPaper('a4', 'landscape');
        } else {

            $data = [];

            $company_via_category = Category::select('companies.*', 'businesses.industry', 'business_types.type')
                ->join('companies', 'companies.id', '=', 'categories.company_id')
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id')
                ->get();

            $employer = Employers::select('users.email', 'users.full_name', 'companies.company_name')
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 2)
                ->where('users.company_id', '!=', 1)
                ->join('users', 'users.id', '=', 'employers.user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->get();

            $workforce_listing = WorkforceListing::query();
            $workforce_plan = WorkforcePlan::query();
            $workforce_plan_tally = WorkforcePlan_certification::query();
            $workforce_list_tally = WorkforceListing_Tally::query();

            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['quarter']) && isset($req['year']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['year']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally =  $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                default:
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
            }

            //add company employee user

            $data = [
                'noselection' => true,
                'companies' => $company_via_category,
                'employer' => $employer,
                'category' => [],
                'wf_plan' =>  $workforce_plan,
                'wf_list' =>  $workforce_listing,
                'wf_list_tally' => $workforce_list_tally,
                'wf_plan_tally' => $workforce_plan_tally,
            ];

            $pdf = PDF::loadView('report_multiplecompany', $data)
                ->setPaper('a4', 'landscape');
        }

        $user = User::findOrfail(Auth::guard('web')->user()->id);

        $content =  $pdf->download('pdf_file.pdf')->getOriginalContent();

        $time = time();

        File::cleanDirectory(Storage::disk('public')->path('pdf/' . $user->id));

        Storage::put('public/pdf/' . $user->id . '/file' .  $user->id . '-' . $time . '.pdf', $content);

        return response()->json([
            'filename' => 'pdf/' . $user->id . '/file' . $user->id . '-' . $time . '.pdf',
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

    public function report_export_company(Request $request)
    {
        $req = $request->data;
        $category = [];
        if (isset($req['company_id'])) {

            $company = Company::select('companies.*', 'businesses.industry', 'business_types.type')
                ->leftJoin('categories', 'companies.id', '=', 'companies.id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id')
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->where('companies.id', $req['company_id']['value'])
                ->get();
        } else {

            $data = [];

            $company_via_category = Category::select('companies.*', 'businesses.industry', 'business_types.type')
                ->join('companies', 'companies.id', '=', 'categories.company_id')
                ->leftJoin('businesses', 'businesses.id', '=', 'categories.business_id')
                ->leftJoin('business_types', 'business_types.id', '=', 'categories.business_type_id')
                ->get();

            $company = $company_via_category;
        }

        $items = $company;
        return (new ReportCompanyExport($items))->download('export.xls');
    }

    public function report_export_employer(Request $request)
    {

        $req = $request->data;
        $company_id = $req['company_id']['value'];
        $one_company = 0;

        if (isset($req['company_id'])) {

            $company_id = $req['company_id']['value'];
            $employer = Employers::select('users.email', 'users.full_name', 'companies.company_name')
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 2)
                ->where('users.company_id', '!=', 1)
                ->where('users.company_id', '=', $company_id)
                ->join('users', 'users.id', '=', 'employers.user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->get();
        } else {
            $employer = Employers::select('users.email', 'users.full_name', 'companies.company_name')
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 2)
                ->where('users.company_id', '!=', 1)
                ->join('users', 'users.id', '=', 'employers.user_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->get();
        }

        $items = $employer;
        return (new ReportEmployersExport($items))->download('export.xls');
    }

    public function report_export_workforceplan(Request $request)
    {
        $req = $request->data;
        $workforce = [];
        if (isset($req['company_id'])) {
            $company_id = $req['company_id']['value'];

            $workforce_plan = WorkforcePlan::query();
            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();
                    break;
                case isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();

                    break;

                case isset($req['year']):

                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();


                    break;

                case isset($req['quarter']):


                    $workforce_plan = $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();


                    break;

                case isset($req['quarter']) && isset($req['year']):


                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();


                    break;

                default:

                    $workforce_plan =  $workforce_plan->select('workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plans.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->get();


                    break;
            }
        } else {


            $workforce_plan = WorkforcePlan::query();


            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):


                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();



                    break;
                case isset($req['year']) && isset($req['year_to']):

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):


                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    break;
                case isset($req['quarter']) && isset($req['year']):


                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['year']):


                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    break;

                case isset($req['quarter']):


                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    break;

                default:

                    $workforce_plan = $workforce_plan->select('companies.company_name', 'workforce_plans.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plans.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
            }
        }

        $workforce = $workforce_plan == null ? [] : $workforce_plan;

        // return response()->json([
        //     'date' => $workforce,
        //     'success' => true,
        //     '_benchmark' => microtime(true) -  $this->time_start
        // ]);

        $items = $workforce;
        return (new ReportWorkforcePlanExport($items))->download('export.xls');
    }
    public function report_export_workforceplantally(Request $request)
    {
        $req = $request->data;
        $workforce = [];
        if (isset($req['company_id'])) {
            $company_id = $req['company_id']['value'];

            $workforce_plan_tally = WorkforcePlan_certification::query();

            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();


                    break;
                case isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();


                    break;

                case isset($req['year']):

                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    break;

                case isset($req['quarter']):

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    break;

                case isset($req['quarter']) && isset($req['year']):


                    $workforce_plan_tally = $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();


                    break;

                default:

                    $workforce_plan_tally =  $workforce_plan_tally->select('workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_plan_certifications.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->get();

                    break;
            }
        } else {

            $workforce_plan_tally = WorkforcePlan_certification::query();
            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['quarter']) && isset($req['year']):

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['year']):

                    $workforce_plan_tally =  $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']):


                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                default:

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
            }
        }

        $workforce =  $workforce_plan_tally == null ? [] :  $workforce_plan_tally;

        // return response()->json([
        //     'date' => $workforce,
        //     'success' => true,
        //     '_benchmark' => microtime(true) -  $this->time_start
        // ]);

        $items = $workforce;
        return (new ReportWorkforcePlanTallyExport($items))->download('export.xls');
    }
    public function report_export_workforcelisting(Request $request)
    {
        $req = $request->data;
        $workforce = [];
        if (isset($req['company_id'])) {
            $company_id = $req['company_id']['value'];

            $workforce_listing = WorkforceListing::query();
            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;
                case isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;

                case isset($req['year']):

                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;

                case isset($req['quarter']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;

                case isset($req['quarter']) && isset($req['year']):
                    $workforce_listing = $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;

                default:
                    $workforce_listing =  $workforce_listing->select('workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listings.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->get();

                    break;
            }
        } else {

            $workforce_listing = WorkforceListing::query();
            $workforce_plan_tally = WorkforcePlan_certification::query();
            $workforce_list_tally = WorkforceListing_Tally::query();

            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['year']) && isset($req['year_to']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();



                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();



                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['quarter']) && isset($req['year']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
                case isset($req['year']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally =  $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                case isset($req['quarter']):
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();


                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                default:
                    $workforce_listing = $workforce_listing->select('companies.company_name', 'workforce_listings.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listings.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    $workforce_plan_tally = $workforce_plan_tally->select('companies.company_name', 'workforce_plan_certifications.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_plan_certifications.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;
            }
        }

        $workforce =   $workforce_listing == null ? [] :  $workforce_listing;

        $items = $workforce;
        return (new ReportWorkforceListExport($items))->download('export.xls');
    }
    public function report_export_workforcelistintally(Request $request)
    {
        $req = $request->data;
        $category = [];
        $workforce = [];
        if (isset($req['company_id'])) {
            $company_id = $req['company_id']['value'];
            $workforce_list_tally = WorkforceListing_Tally::query();
            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();

                    break;

                case isset($req['year']) && isset($req['year_to']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                case isset($req['year']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();

                    break;

                case isset($req['quarter']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                case isset($req['quarter']) && isset($req['year']):

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;

                default:

                    $workforce_list_tally = $workforce_list_tally->select('workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('workforce_listing_tallies.company_id', $company_id)
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->get();
                    break;
            }
        } else {


            $workforce_list_tally = WorkforceListing_Tally::query();

            switch (true) {
                case isset($req['year']) && isset($req['year_to']) && isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;

                case isset($req['year']) && isset($req['year_to']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.year', [$req['year'], $req['year_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;

                case isset($req['quarter']) && isset($req['quarter_to']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->orWhereBetween('documents.quarter', [$req['quarter'], $req['quarter_to']])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['quarter']) && isset($req['year']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
                case isset($req['year']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.year', $req['year'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;

                case isset($req['quarter']):

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->where('documents.quarter', $req['quarter'])
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();
                    break;

                default:

                    $workforce_list_tally = $workforce_list_tally->select('companies.company_name', 'workforce_listing_tallies.*', 'documents.year', 'documents.quarter')
                        ->leftJoin('documents', 'documents.id', '=', 'workforce_listing_tallies.document_id')
                        ->leftJoin('companies', 'companies.id', '=', 'documents.company_id')
                        ->get();

                    break;
            }
        }

        $workforce =   $workforce_list_tally == null ? [] :  $workforce_list_tally;

        $items = $workforce;
        return (new ReportWorkforceListTallyExport($items))->download('export.xls');
    }
}
