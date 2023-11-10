<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Business;
use App\Models\BusinessType;

class EmployerController extends Controller
{
    public function index()
    {
        return Inertia::render('Employer/Index', []);
    }
}
