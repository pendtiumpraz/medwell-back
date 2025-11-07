<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('level')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show form for creating new role
     */
    public function create()
    {
        // If no permissions exist, create empty array
        $permissionsData = Permission::orderBy('group')->get();
        $permissions = $permissionsData->groupBy('group');
        
        // If no permissions in DB, create sample structure
        if ($permissionsData->isEmpty()) {
            $permissions = collect([
                'patients' => collect([]),
                'users' => collect([]),
                'medications' => collect([]),
                'reports' => collect([]),
            ]);
        }
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:10',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'level' => $request->level,
            'permissions' => $request->permissions ?? [],
            'is_system' => false,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role created');

        return redirect()->route('admin.roles.show', $role->id)
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::withCount('users')->findOrFail($id);
        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        
        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show form for editing role
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system && !auth()->user()->role === 'super_admin') {
            abort(403, 'Only Super Admin can edit system roles.');
        }

        // Load permissions
        $permissionsData = Permission::orderBy('group')->get();
        $permissions = $permissionsData->groupBy('group');
        
        // If no permissions in DB, create sample structure
        if ($permissionsData->isEmpty()) {
            $permissions = collect([
                'patients' => collect([]),
                'users' => collect([]),
                'medications' => collect([]),
                'reports' => collect([]),
            ]);
        }
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can edit system roles.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:10',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'level' => $request->level,
            'permissions' => $request->permissions ?? [],
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role updated');

        return redirect()->route('admin.roles.show', $role->id)
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users.');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->log('Role deleted');

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Assign role to user
     */
    public function assignUser(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $role->users()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'User assigned to role successfully.');
    }

    /**
     * Remove user from role
     */
    public function removeUser(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->users()->detach($request->user_id);

        return back()->with('success', 'User removed from role successfully.');
    }
}
