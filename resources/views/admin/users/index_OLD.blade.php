@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            <p class="text-gray-600 mt-1">Manage system users and their roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i> Add New User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Username, email, phone..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="all">All Roles</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="organization_admin" {{ request('role') == 'organization_admin' ? 'selected' : '' }}>Organization Admin</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="clinician" {{ request('role') == 'clinician' ? 'selected' : '' }}>Clinician</option>
                    <option value="health_coach" {{ request('role') == 'health_coach' ? 'selected' : '' }}>Health Coach</option>
                    <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    <option value="support" {{ request('role') == 'support' ? 'selected' : '' }}>Support</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <!-- Show Deleted (Super Admin only) -->
            @if(auth()->user()->role === 'super_admin')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deleted</label>
                <select name="show_deleted" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="0">Active Only</option>
                    <option value="1" {{ request('show_deleted') == '1' ? 'selected' : '' }}>Include Deleted</option>
                </select>
            </div>
            @endif

            <div class="flex items-end">
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 {{ $user->trashed() ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->organization->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $user->role === 'admin' || $user->role === 'organization_admin' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $user->role === 'clinician' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->role === 'patient' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ !in_array($user->role, ['super_admin', 'admin', 'organization_admin', 'clinician', 'patient']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $user->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $routePrefix = auth()->user()->isSuperAdmin() ? 'super-admin' : 'admin';
                            @endphp
                            
                            <div class="flex items-center space-x-3">
                                <!-- View Button -->
                                <a href="{{ route($routePrefix . '.users.show', $user->id) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                    <span class="ml-1 text-xs font-medium">View</span>
                                </a>
                                
                                @if(!$user->trashed())
                                    <!-- Edit Button -->
                                    <a href="{{ route($routePrefix . '.users.edit', $user->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors"
                                       title="Edit User">
                                        <i class="fas fa-edit text-sm"></i>
                                        <span class="ml-1 text-xs font-medium">Edit</span>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                    <!-- Delete Button -->
                                    <form action="{{ route($routePrefix . '.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                title="Delete User">
                                            <i class="fas fa-trash text-sm"></i>
                                            <span class="ml-1 text-xs font-medium">Delete</span>
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    <!-- Deleted Badge -->
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">
                                        DELETED
                                    </span>
                                    
                                    @if(auth()->user()->role === 'super_admin')
                                    <!-- Restore Button -->
                                    <form action="{{ route($routePrefix . '.users.restore', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors"
                                                title="Restore User">
                                            <i class="fas fa-undo text-sm"></i>
                                            <span class="ml-1 text-xs font-medium">Restore</span>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-6xl mb-4 opacity-20"></i>
                            <p class="text-lg">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 animate-slide-in">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 animate-slide-in">
    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
</div>
@endif
@endsection
