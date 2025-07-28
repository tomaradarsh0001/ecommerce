<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index() {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create() {
        return view('permissions.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:permissions']);
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission created.');
    }
    public function checkExists(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $exists = Permission::where('name', $validated['name'])->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
     public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }
   public function toggleStatus(Request $request, Permission $permission)
{
    if ($permission->is_active && $permission->roles()->count() > 0) {
        return back()->with('error', 'Permission is already assigned to a role and cannot be deactivated.');
    }
    $permission->is_active = !$permission->is_active;
    $permission->save();

    return back()->with('success', 'Permission status updated successfully.');
}


    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permission->id,
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

}
