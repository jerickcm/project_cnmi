<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Spatie\Permission\Models\Permission;


class WorkforceController extends Controller
{
    public function plan($id)
    {
        $allPermissions = Permission::pluck('name');
        return Inertia::render('Dol/Edit/WF_Plan', [
            'allPermissions' => $allPermissions
        ]);
    }

    public function list($id)
    {
        $allPermissions = Permission::pluck('name');
        return Inertia::render('Dol/Edit/WF_List', [
            'allPermissions' => $allPermissions
        ]);
    }

    public function plan_tally($id)
    {
        $allPermissions = Permission::pluck('name');
        return Inertia::render('Dol/Edit/WF_Plan_tally', [
            'allPermissions' => $allPermissions
        ]);
    }

    public function list_tally($id)
    {
        $allPermissions = Permission::pluck('name');
        return Inertia::render('Dol/Edit/WF_List_tally', [
            'allPermissions' => $allPermissions
        ]);
    }
}
