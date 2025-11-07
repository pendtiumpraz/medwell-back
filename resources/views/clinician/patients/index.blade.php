@extends('layouts.app')

@section('title', 'My Patients')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">My Patients</h1>
    <p class="text-gray-600 mt-1">Patients assigned to you</p>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search patients..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
            >
        </div>
        <div>
            <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="">All Priorities</option>
                <option value="high">High Priority</option>
                <option value="normal">Normal</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
            Apply Filters
        </button>
    </form>
</div>

<!-- Patients Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($patients as $patient)
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6">
        <!-- Patient Header -->
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-bold text-lg">
                {{ substr($patient->full_name, 0, 2) }}
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800">{{ $patient->full_name }}</h3>
                <p class="text-sm text-gray-500">{{ $patient->age }} years old</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-xs text-blue-600 font-medium">Latest BP</p>
                <p class="text-sm font-bold text-gray-800">
                    {{ $patient->latestVital ? $patient->latestVital->systolic . '/' . $patient->latestVital->diastolic : 'N/A' }}
                </p>
            </div>
            <div class="bg-green-50 rounded-lg p-3">
                <p class="text-xs text-green-600 font-medium">Glucose</p>
                <p class="text-sm font-bold text-gray-800">
                    {{ $patient->latestGlucose ?? 'N/A' }} mg/dL
                </p>
            </div>
        </div>

        <!-- Wearable Status -->
        @if($patient->wearable_type && $patient->wearable_type !== 'none')
        <div class="flex items-center gap-2 mb-4 text-sm">
            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gray-600">
                @if($patient->wearable_type === 'huawei')
                    Huawei Health Connected
                @elseif($patient->wearable_type === 'samsung')
                    Samsung Health Connected
                @elseif($patient->wearable_type === 'fitbit')
                    Fitbit Connected
                @elseif($patient->wearable_type === 'apple')
                    Apple Health Connected
                @else
                    {{ ucfirst($patient->wearable_type) }} Connected
                @endif
            </span>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-2 pt-4 border-t border-gray-100">
            <a href="{{ route('clinician.patients.show', $patient->id) }}" 
               class="flex-1 px-3 py-2 text-center text-sm bg-primary text-white rounded-lg hover:bg-primary-dark">
                View Details
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <p class="text-gray-500">No patients assigned to you yet</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($patients->hasPages())
<div class="mt-6">
    {{ $patients->links() }}
</div>
@endif
@endsection
