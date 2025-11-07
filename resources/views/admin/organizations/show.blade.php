@extends('layouts.app')

@section('title', 'Organization Details')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('super-admin.organizations.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Organizations
        </a>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">{{ $organization->name }}</h1>
        <p class="text-gray-600 mt-1">{{ $organization->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('super-admin.organizations.edit', $organization->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>
            Edit Organization
        </a>
        @if($organization->users_count == 0)
        <form action="{{ route('super-admin.organizations.destroy', $organization->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    onclick="return confirm('Are you sure you want to delete this organization?')">
                <i class="fas fa-trash mr-2"></i>
                Delete
            </button>
        </form>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $organization->users_count }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Patients</p>
                <p class="text-3xl font-bold text-gray-800">{{ $organization->patients_count }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-injured text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Facilities</p>
                <p class="text-3xl font-bold text-gray-800">{{ $organization->facilities_count }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-hospital text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $organization->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $organization->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $organization->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($organization->status) }}
                </span>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Organization Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Organization Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Organization Code</p>
                    <p class="text-base font-medium text-gray-800">{{ $organization->code }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Type</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $organization->type === 'hospital' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $organization->type === 'clinic' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $organization->type === 'pharmacy' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $organization->type === 'laboratory' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $organization->type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($organization->type) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-base font-medium text-gray-800">{{ $organization->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-base font-medium text-gray-800">{{ $organization->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Website</p>
                    @if($organization->website)
                    <a href="{{ $organization->website }}" target="_blank" class="text-base font-medium text-primary hover:text-primary-dark">
                        {{ $organization->website }}
                    </a>
                    @else
                    <p class="text-base font-medium text-gray-800">N/A</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Address</p>
                    <p class="text-base font-medium text-gray-800">
                        @if($organization->address)
                            {{ $organization->address }}<br>
                            {{ $organization->city }}, {{ $organization->state }} {{ $organization->postal_code }}<br>
                            {{ $organization->country }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Stats</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Created</p>
                    <p class="text-base font-medium text-gray-800">{{ $organization->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $organization->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="text-base font-medium text-gray-800">{{ $organization->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $organization->updated_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Organization ID</p>
                    <p class="text-base font-medium text-gray-800">#{{ $organization->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users & Patients -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 font-sora">Recent Users</h2>
            <a href="{{ route('super-admin.users.index') }}?organization={{ $organization->id }}" class="text-primary hover:text-primary-dark text-sm">
                View All →
            </a>
        </div>
        
        @if($recentUsers->count() > 0)
        <div class="space-y-3">
            @foreach($recentUsers->take(5) as $user)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($user->username, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $user->username }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No users yet</p>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 font-sora">Recent Patients</h2>
            <a href="{{ route('super-admin.patients.index') }}?organization={{ $organization->id }}" class="text-primary hover:text-primary-dark text-sm">
                View All →
            </a>
        </div>
        
        @if($recentPatients->count() > 0)
        <div class="space-y-3">
            @foreach($recentPatients->take(5) as $patient)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($patient->full_name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->user->email }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $patient->onboarding_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $patient->onboarding_completed ? 'Active' : 'Pending' }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No patients yet</p>
        @endif
    </div>
</div>
@endsection
