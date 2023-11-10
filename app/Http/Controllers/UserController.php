<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function handleEdit($id)
    {

        $allPermissions = Permission::pluck('name');
        $user = User::findOrFail($id);
        $logged_user = User::findOrFail(Auth::guard('web')->user()->id);

        if ($logged_user->hasRole('SUPERADMIN')) {
            return Inertia::render('User/Edit', [
                'allPermissions' => $allPermissions,
                'user' =>  $user
            ]);
        } else  if ($logged_user->hasRole('ADMIN')) {
            if ($user->hasRole('SUPERADMIN')) {
                return Inertia::render('AccessDenied');
            }
            return Inertia::render('User/Edit', [
                'allPermissions' => $allPermissions,
                'user' =>  $user
            ]);
        } else  if (Auth::guard('web')->user()->id == $id) {
            if ($user->hasRole('SUPERADMIN')) {
                return Inertia::render('AccessDenied');
            }
            if ($user->hasRole('ADMIN')) {
                return Inertia::render('AccessDenied');
            }
            return Inertia::render('User/Edit', [
                'allPermissions' => $allPermissions,
                'user' =>  $user
            ]);
        } else {
            return Inertia::render('AccessDenied');
        }


    }
}
