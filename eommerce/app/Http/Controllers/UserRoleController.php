<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index() {
        $users = User::all();
        $roles = Role::all();
        return view('users.assign-role', compact('users', 'roles'));
    }

    public function assign(Request $request) {
        $user = User::find($request->user_id);
        $user->syncRoles([$request->role]);
        return redirect()->back()->with('success', 'Role assigned to user.');
    }

}
