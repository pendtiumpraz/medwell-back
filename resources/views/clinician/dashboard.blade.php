@extends('layouts.app')

@section('title', 'Clinician Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-8 text-white mb-6 shadow-xl">
        <h1 class="text-3xl font-bold mb-2">Welcome, Dr. {{ auth()->user()->username }}! 👨‍⚕️</h1>
        <p class="text-green-100">Clinician Dashboard - Monitor your patients' health</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- My Patients -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">My Patients</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ auth()->user()->assignedPatients()->count() }}
                    </h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Alerts -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Alerts</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        @php
                            $myPatientIds = auth()->user()->assignedPatients()->pluck('patient_profiles.id');
                            $alertsCount = \App\Models\HealthAlert::whereIn('patient_id', $myPatientIds)
                                ->whereNull('resolved_at')->count();
                        @endphp
                        {{ $alertsCount }}
                    </h3>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Today's Appointments</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending Tasks</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Patients -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('clinician.patients.index') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">View All My Patients</span>
                </a>
                <a href="{{ route('clinician.patients.index') }}" class="flex items-center gap-3 p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Health Alerts</span>
                    @if(isset($alertsCount) && $alertsCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $alertsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('clinician.patients.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Review Vital Signs</span>
                </a>
                <a href="{{ route('clinician.patients.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Manage Medications</span>
                </a>
            </div>
        </div>

        <!-- Recent Patients -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Patients</h2>
            <div class="space-y-3">
                @php
                    $recentPatients = auth()->user()->assignedPatients()
                        ->with('user')
                        ->latest('patient_clinician.created_at')
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($recentPatients as $patient)
                <a href="{{ route('clinician.patients.show', $patient->id) }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($patient->full_name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->user->email ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}y
                    </span>
                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm">No patients assigned yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Alerts -->
    @if(isset($alertsCount) && $alertsCount > 0)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Health Alerts</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Alert Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Severity</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $recentAlerts = \App\Models\HealthAlert::whereIn('patient_id', $myPatientIds)
                            ->whereNull('resolved_at')
                            ->with('patient')
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @foreach($recentAlerts as $alert)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $alert->patient->full_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $alert->alert_type)) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $alert->severity === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $alert->severity === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $alert->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $alert->severity === 'low' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($alert->severity) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $alert->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('clinician.patients.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
