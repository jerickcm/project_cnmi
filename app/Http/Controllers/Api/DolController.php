<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Dol_Employer_Notes;
use App\Models\Employers;
use App\Models\Logs;
use App\Models\Document;
use App\Models\Company;
use App\Models\Category;
use App\Models\Business;
use App\Models\BusinessType;


class DolController extends Controller
{

    public function getSearchfield(Request $request)
    {

        switch ($request->field) {
            case "industry":
                $data =  Business::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
                break;
            case "type":
                $data =  BusinessType::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
                break;
            case "company_name":
                $data =  Company::select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
                break;
            case "full_name":
                $data =    DB::table('users')->select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
                break;
            case "email":
                $data =  DB::table('users')->select($request->field)->groupBy($request->field)->where([[$request->field, 'LIKE', "%" . $request->searchValue . "%"]])->get();
                break;

            default:
        }

        return response()->json([
            'data' => $data,
            'success' => true,
            '_benchmark' => microtime(true) -  $this->time_start
        ]);
    }

  
}
