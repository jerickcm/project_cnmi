<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        return Inertia::render('Report/Index', [
            'company' => $request->company,
            'year' => $request->year,
            'quarter' => $request->quarter,
            'industry_id' => $request->industry_id,
            'type_id' => $request->type_id,
            'year_to' => $request->year_to,
            'quarter_to' => $request->quarter_to,
        ]);
    }
}
