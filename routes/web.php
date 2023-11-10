<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController as UserController;
use Illuminate\Support\Facades\Mail;



// page
use App\Http\Controllers\EmployerController as EmployerController;
use App\Http\Controllers\DolController as DolController;
use App\Http\Controllers\WorkforceController as WorkforceController;
use App\Http\Controllers\ReportController as ReportController;
use App\Http\Controllers\CompanyController as CompanyController;
use App\Http\Controllers\CategoryController as CategoryController;

// api
use App\Http\Controllers\Api\DolController as ApiDolController;
use App\Http\Controllers\Api\NotesController as ApiNotesController;
use App\Http\Controllers\Api\WorkforceController as ApiWorkforceController;
use App\Http\Controllers\Api\CompanyController as ApiCompanyController;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\LogsController as ApiLogsController;
use App\Http\Controllers\Api\RoleController as ApiRoleController;
use App\Http\Controllers\Api\EmployerController as ApiEmployerController;
use App\Http\Controllers\Api\DocumentController as ApiDocumentController;
use App\Http\Controllers\Api\ReportController as ApiReportController;


Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::get('/maintenance', function () {
    return Inertia::render('Maintenance');
})->name('maintainance');


Route::get('/maintenance', function () {
    return Inertia::render('Maintenance');
})->name('maintainance');

Route::group(['prefix' => 'report', 'middleware' => ['throttle:500,1']], function () {

    Route::name('business')->get('/business-get/', [ApiDashboardController::class, 'business'])->middleware(['auth', 'verified']);
    Route::name('export-report-company')->post('/export-report-company', [ApiReportController::class, 'report_export_company'])->middleware(['auth', 'verified']);
    Route::name('export-report-employer')->post('/export-report-employer', [ApiReportController::class, 'report_export_employer'])->middleware(['auth', 'verified']);
    Route::name('export-report-workforceplan')->post('/export-report-workforceplan', [ApiReportController::class, 'report_export_workforceplan'])->middleware(['auth', 'verified']);
    Route::name('export-report-workforceplangtally')->post('/export-report-workforceplangtally', [ApiReportController::class, 'report_export_workforceplantally'])->middleware(['auth', 'verified']);
    Route::name('export-report-workforcelisting')->post('/export-report-workforcelisting', [ApiReportController::class, 'report_export_workforcelisting'])->middleware(['auth', 'verified']);
    Route::name('export-report-workforcelistintally')->post('/export-report-workforcelistintally', [ApiReportController::class, 'report_export_workforcelistintally'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'data', 'middleware' => ['throttle:500,1']], function () {

    Route::name('permission_host')->get('/permissionhost-get/', [ApiDashboardController::class, 'permission'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'settings', 'middleware' => ['permission:Access-Page-Categories', 'throttle:500,1']], function () {

    Route::name('category-index')
        ->get('business-categories', [CategoryController::class, 'index'])
        ->middleware(['auth', 'verified']);

    Route::name('category-edit')
        ->get('/category/{id}', [CategoryController::class, 'edit'])
        ->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'settings', 'middleware' => ['permission:Access-Page-Company', 'throttle:500,1']], function () {

    Route::name('company-index')
        ->get('company', [CompanyController::class, 'index'])
        ->middleware(['auth', 'verified']);

    Route::name('company-create')
        ->get('/company-create', [CompanyController::class, 'create'])
        ->middleware(['auth', 'verified', 'permission:Action Create Company']);

    Route::name('company-edit')
        ->get('company-edit/{id}', [CompanyController::class, 'edit'])
        ->middleware(['auth', 'verified', 'permission:Action Edit Company']);
});

Route::group(['prefix' => 'pages', 'middleware' => ['throttle:500,1']], function () {
    Route::name('about')->get('/about', function () {
        return Inertia::render('About');
    })->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'workforce', 'middleware' => ['throttle:500,1']], function () {

    /** Api */
    Route::name('delete-plan')->post('/delete-wf-plan/{id}/delete-plan', [ApiWorkforceController::class, 'destroy_wf_plan'])->middleware(['auth', 'verified']);
    Route::name('delete-listing')->post('/delete-wf-listing/{id}/delete-listing', [ApiWorkforceController::class, 'destroy_wf_listing'])->middleware(['auth', 'verified']);

    Route::name('delete-wflist')->post('/delete-list/{id}/delete-wf-list', [ApiWorkforceController::class, 'destroy_list'])->middleware(['auth', 'verified']);
    Route::name('delete-wflisttally')->post('/delete-listtally/{id}/delete-wf-tally', [ApiWorkforceController::class, 'destroy_listtally'])->middleware(['auth', 'verified']);
    Route::name('delete-wfplan')->post('/delete-plan/{id}/delete-wf-plan', [ApiWorkforceController::class, 'destroy_plan'])->middleware(['auth', 'verified']);
    Route::name('delete-wfplantally')->post('/delete-plantally/{id}/delete-wf-plan-tally', [ApiWorkforceController::class, 'destroy_plantally'])->middleware(['auth', 'verified']);

    Route::name('wfplan-get')->get('/plan-get/{id}/show-plan', [ApiWorkforceController::class, 'show_plan'])->middleware(['auth', 'verified']);
    Route::name('wfplan-tally-get')->get('/plan-tally-get/{id}/show-plan-tally', [ApiWorkforceController::class, 'show_plan_tally'])->middleware(['auth', 'verified']);
    Route::name('wflist-get')->get('/list-get/{id}/get-wf-list', [ApiWorkforceController::class, 'show_list'])->middleware(['auth', 'verified']);
    Route::name('wflist-tally-get')->get('/list-tally-get/{id}/get-list-tally', [ApiWorkforceController::class, 'show_list_tally'])->middleware(['auth', 'verified']);

    Route::name('wfplan-update')->post('/plan-update/{id}', [ApiWorkforceController::class, 'update_plan'])->middleware(['auth', 'verified']);
    Route::name('wfplan-tally-update')->post('/plan-tally-update/{id}', [ApiWorkforceController::class, 'update_plan_tally'])->middleware(['auth', 'verified']);
    Route::name('wflist-update')->post('/list-update/{id}', [ApiWorkforceController::class, 'update_list'])->middleware(['auth', 'verified']);
    Route::name('wflist-tally-update')->post('/list-tally-update/{id}', [ApiWorkforceController::class, 'update_list_tally'])->middleware(['auth', 'verified']);

    Route::name('document-fetch-workforce-plan')->post('/fetch_workforce_plan/{id}', [ApiWorkforceController::class, 'fetch_workforce_plan'])->middleware(['auth', 'verified']);
    Route::name('document-fetch-workforce-listing')->post('/fetch_workforce_listing/{id}', [ApiWorkforceController::class, 'fetch_workforce_listing'])->middleware(['auth', 'verified']);
    Route::name('document-fetch-workforce-plan-tally')->post('/fetch_workforce_plan_tally/{id}', [ApiWorkforceController::class, 'fetch_workforce_plan_tally'])->middleware(['auth', 'verified']);
    Route::name('document-fetch-workforce-listing-tally')->post('/fetch_workforce_listing_tally/{id}', [ApiWorkforceController::class, 'fetch_workforce_listing_tally'])->middleware(['auth', 'verified']);

    /** Pages */

    Route::name('wfplan-edit')->get('/plan-edit/{id}', [WorkforceController::class, 'plan'])->middleware(['auth', 'verified']);
    Route::name('wfplan-tally-edit')->get('/plan-tally-edit/{id}', [WorkforceController::class, 'plan_tally'])->middleware(['auth', 'verified']);
    Route::name('wflist-edit')->get('/list-edit/{id}', [WorkforceController::class, 'list'])->middleware(['auth', 'verified']);
    Route::name('wflist-tally-edit')->get('/list-tally-edit/{id}', [WorkforceController::class, 'list_tally'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'documents', 'middleware' => ['throttle:500,1']], function () {

    /** Api */

    Route::name('document-fetch')->post('/fetch/{company_id}', [ApiDocumentController::class, 'fetch'])->middleware(['auth', 'verified']);
    Route::name('document-fetch-plan')->post('/fetch_plan/{id}', [ApiDocumentController::class, 'fetch_plan'])->middleware(['auth', 'verified']);
    Route::name('document-fetch-listing')->post('/fetch_listing/{id}', [ApiDocumentController::class, 'fetch_listing'])->middleware(['auth', 'verified']);
    Route::name('document-delete')->post('/delete/{id}', [ApiDocumentController::class, 'destroy'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'company', 'middleware' => ['throttle:500,1']], function () {

    /** Api */

    Route::name('company-update')->post('/company-update/{id}', [ApiCompanyController::class, 'update'])->middleware(['auth', 'verified']);
    Route::name('company-delete')->post('/company-delete/{id}', [ApiCompanyController::class, 'destroy'])->middleware(['auth', 'verified']);
    Route::name('company-get')->get('/get_company/{id}', [ApiCompanyController::class, 'show'])->middleware(['auth', 'verified']);
    Route::name('company-get-by-name')->get('/get_company_by_name/{name}', [ApiCompanyController::class, 'show_company'])->middleware(['auth', 'verified']);
    Route::name('company-store')->post('/store', [ApiCompanyController::class, 'store'])->middleware(['auth', 'verified']);

    Route::name('select-company')->post('/getSelectfield', [ApiCompanyController::class, 'getSearchfield'])->middleware(['auth', 'verified']);
    Route::name('select-company-validonly')->post('/getSelectfield-validonly', [ApiCompanyController::class, 'getSearchfield_validonly'])->middleware(['auth', 'verified']);
    Route::name('get-company-list')->post('/get-companies', [ApiCompanyController::class, 'fetch_companies'])->middleware(['auth', 'verified']);
    Route::name('export-company')->post('/export-companies', [ApiCompanyController::class, 'export_companies'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'category', 'middleware' => ['throttle:500,1']], function () {

    /** Api */
    Route::name('businesstype-store')->post('/businesstype-store', [ApiCategoryController::class, 'store_businesstype'])->middleware(['auth', 'verified']);
    Route::name('businessindustry-store')->post('/businessindustry-store', [ApiCategoryController::class, 'store_businessindustry'])->middleware(['auth', 'verified']);
    Route::name('category-get')->get('/category-get/{id}', [ApiCategoryController::class, 'show_with_type'])->middleware(['auth', 'verified']);
    Route::name('category-delete')->post('/category-delete/{id}', [ApiCategoryController::class, 'destroy'])->middleware(['auth', 'verified']);

    Route::name('category-type-delete')->post('/category-type-delete/{id}', [ApiCategoryController::class, 'destroy_business_type'])->middleware(['auth', 'verified']);
    Route::name('category-type-update')->post('/category-type-update', [ApiCategoryController::class, 'update_business_type'])->middleware(['auth', 'verified']);
    Route::name('category-industry-update')->post('/category-industry-update', [ApiCategoryController::class, 'update_business_industry'])->middleware(['auth', 'verified']);
    Route::name('get-bussiness-category-list')->post('/get-business-categories', [ApiCategoryController::class, 'fetch_business_categories'])->middleware(['auth', 'verified']);

    Route::name('get-bussiness-type-list')->post('/get-business-type/{id}', [ApiCategoryController::class, 'fetch_business_type'])->middleware(['auth', 'verified']);
    Route::name('categories-export')->post('/categories/export', [ApiCategoryController::class, 'export'])->middleware(['auth', 'verified']);
    Route::name('categories-get')->get('/get_categories/{id}', [ApiCategoryController::class, 'show'])->middleware(['auth', 'verified']);
    Route::name('categories-update')->post('/update_category/{company_id}', [ApiCategoryController::class, 'update'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'employer', 'middleware' => ['throttle:500,1']], function () {

    /** Api */
    Route::name('employer-fetch')->post('/fetch', [ApiEmployerController::class, 'all_employers'])->middleware(['auth', 'verified']);
    Route::name('employer-get')->get('/get/{id}', [ApiEmployerController::class, 'show'])->middleware(['auth', 'verified']);
    Route::name('employer-update')->post('/update/{id}', [ApiEmployerController::class, 'update'])->middleware(['auth', 'verified']);

    Route::name('employer-update-dol')->post('/update-employer-dol/{id}', [ApiEmployerController::class, 'update_by_dol'])->middleware(['auth', 'verified']);
    Route::name('employer-yearlock-update')->post('/update_year/{id}', [ApiEmployerController::class, 'updateYearLock'])->middleware(['auth', 'verified']);
    Route::name('employer-quarterlock-update')->post('/update_quater/{id}', [ApiEmployerController::class, 'updateQuarterLock'])->middleware(['auth', 'verified']);
    Route::name('employer-store-documents')->post('/upload-file', [ApiEmployerController::class, 'upload'])->middleware(['auth', 'verified']);

    /** Pages */

    Route::name('employer-index')->get('/', [EmployerController::class, 'index'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'business', 'middleware' => ['throttle:500,1']], function () {
    /** Api */
    Route::name('business-delete')->post('/category-business-delete/{id}', [ApiCategoryController::class, 'destroy_business'])->middleware(['auth', 'verified']);
    Route::name('get-industry-by-name')->get('/get-industry-by-name/{name}', [ApiCategoryController::class, 'get_industry_by_name'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'note', 'middleware' => ['throttle:500,1']], function () {
    /** Api */
    Route::name('note-get-by-employer')->get('/get-notes/{id}', [ApiNotesController::class, 'get_notes'])->middleware(['auth', 'verified']);
    Route::name('note-get-by-company-clearance')->get('/get-notes-clearance/{id}', [ApiNotesController::class, 'get_notes_clearance'])->middleware(['auth', 'verified']);
    Route::name('note-delete')->post('/delete-note/{id}', [ApiNotesController::class, 'delete_note'])->middleware(['auth', 'verified']);
    Route::name('note-delete-clearance')->post('/delete-note-clearance/{id}', [ApiNotesController::class, 'delete_note_clearance'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'dol', 'middleware' => ['role:DOL STAFF|ADMIN|SUPERADMIN', 'throttle:500,1']], function () {

    Route::name('export-reportpdf')->post('/export/reportpdf', [ApiReportController::class, 'index'])->middleware(['auth', 'verified']);

    Route::name('report-index')->get('/report/', [ReportController::class, 'index'])->middleware(['auth', 'verified', 'permission:Access-Page-Report']);

    Route::name('export-plan')->post('/plan/export/{id}', [ApiDocumentController::class, 'export_fetch_plan'])->middleware(['auth', 'verified']);

    Route::name('export-listing')->post('/listing/export/{id}', [ApiDocumentController::class, 'export_fetch_listing'])->middleware(['auth', 'verified']);

    /** 4 inner data table start */
    Route::name('export-plan-data')->post('/plan-data/export/{id}', [ApiWorkforceController::class, 'export_plan_data'])->middleware(['auth', 'verified']);
    Route::name('export-plan-tally')->post('/plan-tally/export/{id}', [ApiWorkforceController::class, 'export_plan_tally'])->middleware(['auth', 'verified']);
    Route::name('export-listing-data')->post('/listing-data/export/{id}', [ApiWorkforceController::class, 'export_listing_data'])->middleware(['auth', 'verified']);
    Route::name('export-listing-tally')->post('/listing-tally/export/{id}', [ApiWorkforceController::class, 'export_listing_tally'])->middleware(['auth', 'verified']);
    /** 4 inner data table end */

    Route::name('dol-multiselect')->post('/multiselect', [ApiDolController::class, 'getSearchfield'])->middleware(['auth', 'verified']);
    Route::name('dol-index')->get('/', [DolController::class, 'index'])->middleware(['auth', 'verified']);

    Route::name('dol-store-note')->post('/store_note', [ApiNotesController::class, 'store_note'])->middleware(['auth', 'verified']);
    Route::name('dol-store-note-clearance')->post('/store_note_clearance', [ApiNotesController::class, 'store_note_clearance'])->middleware(['auth', 'verified']);

    Route::name('dol-edit')->get('/edit/{id}', [DolController::class, 'edit_employer'])->middleware(['auth', 'verified']);

    Route::name('dol-edit-wfplan')->get('/edit/wfplan/{id}', [DolController::class, 'edit_employer'])->middleware(['auth', 'verified']);
    Route::name('dol-edit-wfplan_tally')->get('/edit/wfplan_tally/{id}', [DolController::class, 'edit_employer'])->middleware(['auth', 'verified']);

    Route::name('dol-edit-wflisting')->get('/edit/wfplan/{id}', [DolController::class, 'edit_employer'])->middleware(['auth', 'verified']);
    Route::name('dol-edit-wflisting_tally')->get('/edit/wfplan_tally/{id}', [DolController::class, 'edit_employer'])->middleware(['auth', 'verified']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['permission:Access-Page-Dashboard', 'throttle:500,1']], function () {

    Route::name('dashboard')->get('/', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified']);

    Route::name('dashboard-data')->get('/get', [ApiDashboardController::class, 'index']);

    Route::name('business-data')->get('/business/get', [ApiDashboardController::class, 'business']);
    Route::name('dashboard-periodic')->get('/dashboard-periodic/{quarter}/{year}', [ApiDashboardController::class, 'dashboard_periodic']);
});

/* User Page */
Route::group(['prefix' => 'users', 'middleware' => ['permission:Access-Page-User', 'throttle:500,1']], function () {

    /* index */
    Route::name('user')->get('/', function () {
        return Inertia::render('User/Index');
    })->middleware(['auth', 'verified']);

    Route::name('user-create')->get('/create', function () {
        return Inertia::render('User/Create');
    })->middleware(['auth', 'verified']);

    /* edit */
    Route::name('user-edit')->get('/edit/{id}', [UserController::class, 'handleEdit'])->middleware(['auth', 'verified']);

    /* changepassword */
    Route::name('user-change-password')->get('/change-password/{id}', function () {
        return Inertia::render('User/ChangePassword');
    })->middleware(['auth', 'verified']);

    /* admin reset password */
    Route::name('user-reset-password')->get('/reset-password/{id}', function () {
        return Inertia::render('User/ResetPassword');
    })->middleware(['auth', 'verified']);

    /* api */
    Route::post('/fetch', [ApiUserController::class, 'fetch']);
    Route::post('/export', [ApiUserController::class, 'exportdata']);
    Route::delete('/delete/{id}/{user_id}', [ApiUserController::class, 'delete_user']);
    Route::get('/{id}', [ApiUserController::class, 'index_user']);
    Route::put('/changepassword/{id}', [ApiUserController::class, 'change_password']);
    Route::put('/resetpassword/{id}', [ApiUserController::class, 'reset_password']);
    Route::post('/updatePermissions', [ApiUserController::class, 'updatePermissions']);
});

Route::group(['prefix' => 'logs', 'middleware' => 'throttle:500,1'], function () {

    Route::name('logs')->get('/', function () {
        return Inertia::render('Logs/Index');
    })->middleware(['auth', 'verified']);

    /* api */
    Route::post('/search', [ApiLogsController::class, 'search']);
    Route::post('/fetch', [ApiLogsController::class, 'fetch']);
});

Route::group(['prefix' => 'roles', 'middleware' => ['permission:Action Settings Roles', 'throttle:500,1']], function () {

    Route::get('/', function () {
        return Inertia::render('Roles');
    })->middleware(['auth', 'verified'])->name('roles');

    /* api */
    Route::get('/index', [ApiRoleController::class, 'index']);
    Route::get('/edit', [ApiRoleController::class, 'index_edit']);
    Route::get('/user_edit', [ApiRoleController::class, 'index_user_edit']);
    Route::post('/update_all', [ApiRoleController::class, 'update_all']);
});

Route::group(['prefix' => 'cstm', 'middleware' => 'throttle:500,1'], function () {

    Route::delete('/users/{id}/{user_id}', [ApiUserController::class, 'delete_user']);
});


// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
