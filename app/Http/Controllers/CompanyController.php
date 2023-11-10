<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {
        return Inertia::render('_Company/Index', []);
    }

    public function create()
    {
        return Inertia::render('_Company/Create', []);
    }

    public function edit($id)
    {
        return Inertia::render('_Company/Edit', []);
    }
}
