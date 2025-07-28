<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
        {
            $query = Role::query();

            // Filter by search
            if ($request->has('search') && $request->search !== null) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter by status
            if ($request->has('status') && $request->status !== null) {
                if ($request->status == 'active') {
                    $query->where('is_active', 1);
                } elseif ($request->status == 'inactive') {
                    $query->where('is_active', 0);
                }
            }

            $roles = $query->with('permissions')->paginate(10);

            return view('roles.index', compact('roles'));
        }


    public function create() {
        return view('roles.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:roles']);
        Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function toggleStatus(Request $request, Role $role)
{
    // Trying to deactivate the role
    if ($role->is_active && $role->users()->count() > 0) {
        return back()->with('error', 'This role is assigned to one or more users and cannot be deactivated.');
    }

    // Safe to toggle status
    $role->is_active = !$role->is_active;
    $role->save();

    return back()->with('success', 'Role status updated successfully.');
}

    // public function toggleStatus(Role $role)
    // {
    //     $role->update(['is_active' => !$role->is_active]);
        
    //     return back()->with('success', 'Role status updated successfully');
    // }
    public function checkExists(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $exists = Role::where('name', $validated['name'])->exists();
        
        return response()->json([
            'exists' => $exists
        ]);
    }
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
    public function editPermissions(Role $role)
{
    $activePermissions = Permission::where('is_active', true)->get();
    return view('roles.edit-permissions', compact('role', 'activePermissions'));
}

        // In your Permission model:
        public function scopeActive($query)
        {
            return $query->where('is_active', true);
        }

    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Permissions updated successfully');
    }
    
}


