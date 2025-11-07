@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 text-white mb-6 shadow-xl">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->username }}! 👋</h1>
        <p class="text-blue-100">Admin Dashboard - {{ auth()->user()->organization->name ?? 'Medwell' }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Patients -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        @php
                            $patientCount = auth()->user()->role === 'super_admin' 
                                ? \App\Models\PatientProfile::count() 
                                : \App\Models\PatientProfile::whereHas('user', function($q) {
                                    $q->where('organization_id', auth()->user()->organization_id);
                                })->count();
                        @endphp
                        {{ $patientCount }}
                    </h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Staff</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        @php
                            $staffCount = auth()->user()->role === 'super_admin'
                                ? \App\Models\User::whereIn('role', ['admin', 'clinician', 'health_coach'])->count()
                                : \App\Models\User::where('organization_id', auth()->user()->organization_id)
                                    ->whereIn('role', ['admin', 'clinician', 'health_coach'])->count();
                        @endphp
                        {{ $staffCount }}
                    </h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Alerts -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Alerts</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\HealthAlert::whereNull('resolved_at')->count() }}</h3>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Medications -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Medications</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Medication::count() }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.patients.create') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Add New Patient</span>
                </a>
                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Add New User</span>
                </a>
                <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">View All Patients</span>
                </a>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Patients</h2>
            <div class="space-y-3">
                @php
                    $recentPatients = auth()->user()->role === 'super_admin'
                        ? \App\Models\PatientProfile::with('user')->latest()->take(5)->get()
                        : \App\Models\PatientProfile::with('user')
                            ->whereHas('user', function($q) {
                                $q->where('organization_id', auth()->user()->organization_id);
                            })->latest()->take(5)->get();
                @endphp
                
                @forelse($recentPatients as $patient)
                <a href="{{ route('admin.patients.show', $patient->id) }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($patient->full_name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->user->email }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $patient->created_at->diffForHumans() }}</span>
                </a>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">No patients yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">System Information</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Database</p>
                <p class="font-bold text-green-600">Online</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">API Status</p>
                <p class="font-bold text-green-600">Running</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Laravel</p>
                <p class="font-bold text-purple-600">{{ app()->version() }}</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Your Role</p>
                <p class="font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
