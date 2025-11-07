<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display listing of users (with soft deleted for super admin)
     */
    public function index(Request $request)
    {
        $query = User::with(['organization', 'department', 'roles']);

        // Filter by organization (non-super admin)
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Include soft deleted (Super Admin only)
        if ($request->filled('show_deleted') && $request->show_deleted == '1' && auth()->user()->isSuperAdmin()) {
            $query->withTrashed();
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show form for creating new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:organization_admin,admin,clinician,health_coach',
            'phone' => 'nullable|string|max:50',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'organization_id' => auth()->user()->organization_id,
            'department_id' => $request->department_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'phone' => $request->phone,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User created by admin');

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with(['organization', 'department', 'roles'])->findOrFail($id);

        // Check access
        if (!auth()->user()->isSuperAdmin() && $user->organization_id != auth()->user()->organization_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Get user data as JSON for modal editing
     */
    public function getJson($id)
    {
        $user = User::findOrFail($id);
        
        // Check access
        if (!auth()->user()->isSuperAdmin() && $user->organization_id != auth()->user()->organization_id) {
            abort(403, 'Unauthorized access.');
        }

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'department_id' => $user->department_id,
            'status' => $user->status,
        ]);
    }

    /**
     * Show form for editing user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        // Check access
        if (!auth()->user()->isSuperAdmin() && $user->organization_id != auth()->user()->organization_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:organization_admin,admin,clinician,health_coach',
            'status' => 'required|in:active,inactive,suspended',
            'phone' => 'nullable|string|max:50',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User updated by admin');

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User soft deleted by admin');

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Restore soft deleted user (Super Admin only)
     */
    public function restore($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can restore users.');
        }

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User restored by super admin');

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete user (Super Admin only)
     */
    public function forceDelete($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can permanently delete users.');
        }

        $user = User::withTrashed()->findOrFail($id);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User permanently deleted by super admin');

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Password changed by admin');

        return back()->with('success', 'Password updated successfully.');
    }
}
