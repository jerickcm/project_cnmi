<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('_Categories/Index', []);
    }

    public function edit()
    {
        return Inertia::render('_Categories/Edit', []);
    }

    public function create()
    {
        return Inertia::render('_Categories/Create', []);
    }
}
