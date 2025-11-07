@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Roles & Permissions</h1>
            <p class="text-gray-600 mt-1">Manage system roles and permissions</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
            <i class="fas fa-plus mr-2"></i> Create New Role
        </a>
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    @php
                        // Icon mapping per role
                        $roleIcons = [
                            'super_admin' => ['icon' => 'fa-crown', 'gradient' => 'from-red-500 to-red-700'],
                            'organization_admin' => ['icon' => 'fa-building-user', 'gradient' => 'from-orange-500 to-orange-700'],
                            'admin' => ['icon' => 'fa-user-shield', 'gradient' => 'from-blue-500 to-blue-700'],
                            'clinician' => ['icon' => 'fa-user-doctor', 'gradient' => 'from-green-500 to-green-700'],
                            'health_coach' => ['icon' => 'fa-heartbeat', 'gradient' => 'from-pink-500 to-pink-700'],
                            'patient' => ['icon' => 'fa-user-injured', 'gradient' => 'from-purple-500 to-purple-700'],
                            'support' => ['icon' => 'fa-headset', 'gradient' => 'from-cyan-500 to-cyan-700'],
                            'manager' => ['icon' => 'fa-chart-line', 'gradient' => 'from-indigo-500 to-indigo-700'],
                        ];
                        $roleIcon = $roleIcons[$role->name] ?? ['icon' => 'fa-shield-alt', 'gradient' => 'from-gray-500 to-gray-700'];
                    @endphp
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br {{ $roleIcon['gradient'] }} flex items-center justify-center text-white shadow-lg">
                        <i class="fas {{ $roleIcon['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $role->display_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $role->name }}</p>
                    </div>
                </div>
                @if($role->is_system)
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">System</span>
                @endif
            </div>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $role->description }}</p>

            <div class="flex items-center justify-between mb-4 bg-gray-50 rounded-lg p-3">
                <div class="text-center">
                    <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mb-1">
                        <i class="fas fa-layer-group"></i>
                        <span>Level</span>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">{{ $role->level }}</p>
                </div>
                <div class="text-center border-l border-r border-gray-200 px-4">
                    <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mb-1">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">{{ $role->users_count }}</p>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center gap-1 text-xs text-gray-500 mb-1">
                        <i class="fas fa-key"></i>
                        <span>Permissions</span>
                    </div>
                    <p class="font-bold text-gray-800 text-lg">{{ is_array($role->permissions) ? count($role->permissions) : 0 }}</p>
                </div>
            </div>

            @php
                $routePrefix = auth()->user()->isSuperAdmin() ? 'super-admin' : 'admin';
            @endphp
            <div class="flex gap-2 justify-center">
                <a href="{{ route($routePrefix . '.roles.show', $role->id) }}" class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors" title="View Details">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                </a>
                @if(!$role->is_system || auth()->user()->role === 'super_admin')
                <a href="{{ route($routePrefix . '.roles.edit', $role->id) }}" class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors" title="Edit Role">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-shield-alt text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No roles found</p>
        </div>
        @endforelse
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif
@endsection
