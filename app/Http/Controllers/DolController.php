<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Business;
use App\Models\BusinessType;
use Illuminate\Support\Facades\DB;

class DolController extends Controller
{
    public function index()
    {
        return Inertia::render('Dol/Index', [
            
        ]);
    }

    public function edit_employer($id)
    {
        return Inertia::render('Dol/EditEmployer', [
     
        ]);
    }
}
