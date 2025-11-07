@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Roles
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $role->display_name }}</h1>
                <p class="text-gray-600 mt-1">{{ $role->description }}</p>
            </div>
            <div class="flex gap-2">
                @if(!$role->is_system || auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @if(!$role->is_system)
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
                @endif
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Role Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Role Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Role Name</p>
                        <p class="font-semibold text-gray-800">{{ $role->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Display Name</p>
                        <p class="font-semibold text-gray-800">{{ $role->display_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Level</p>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">Level {{ $role->level }}</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Type</p>
                        <span class="px-3 py-1 {{ $role->is_system ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm font-semibold">
                            {{ $role->is_system ? 'System Role' : 'Custom Role' }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Description</p>
                        <p class="text-gray-800">{{ $role->description ?? 'No description' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Created At</p>
                        <p class="text-gray-800">{{ $role->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Updated At</p>
                        <p class="text-gray-800">{{ $role->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Permissions</h2>
                @if(is_array($role->permissions) && count($role->permissions) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($role->permissions as $permission)
                    <div class="flex items-center gap-2 p-2 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-sm text-gray-700">{{ $permission }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No permissions assigned</p>
                @endif
            </div>

            <!-- Assigned Users -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Assigned Users ({{ $role->users_count }})</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($role->users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $user->username }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    No users assigned to this role
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stats Sidebar -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl shadow-lg p-6 text-white">
                <h2 class="text-xl font-bold mb-4">Role Statistics</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-purple-200 text-sm">Total Users</p>
                        <p class="text-3xl font-bold">{{ $role->users_count }}</p>
                    </div>
                    <div class="border-t border-purple-500 pt-4">
                        <p class="text-purple-200 text-sm">Total Permissions</p>
                        <p class="text-3xl font-bold">{{ is_array($role->permissions) ? count($role->permissions) : 0 }}</p>
                    </div>
                    <div class="border-t border-purple-500 pt-4">
                        <p class="text-purple-200 text-sm">Role Level</p>
                        <p class="text-3xl font-bold">{{ $role->level }}</p>
                    </div>
                </div>
            </div>

            @if(!$role->is_system)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role? All users will lose this role.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 py-2 px-4 rounded-lg text-sm font-semibold">
                            <i class="fas fa-trash mr-2"></i> Delete Role
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif
@endsection
