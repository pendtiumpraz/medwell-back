@extends('layouts.app')

@section('title', 'My Vital Signs')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">My Vital Signs</h1>
            <p class="text-gray-600 mt-1">Track your health metrics</p>
        </div>
        <a href="{{ route('patient.vitals.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark shadow-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Log Vitals
        </a>
    </div>
</div>

<!-- Latest Vitals Summary -->
@if($latestVital)
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Latest Reading</h3>
    
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @if($latestVital->systolic && $latestVital->diastolic)
        <div class="p-4 bg-red-50 rounded-lg">
            <p class="text-xs text-red-600 font-medium mb-1">Blood Pressure</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->systolic }}/{{ $latestVital->diastolic }}</p>
            <p class="text-xs text-gray-500 mt-1">mmHg</p>
        </div>
        @endif

        @if($latestVital->glucose_level)
        <div class="p-4 bg-purple-50 rounded-lg">
            <p class="text-xs text-purple-600 font-medium mb-1">Glucose</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->glucose_level }}</p>
            <p class="text-xs text-gray-500 mt-1">mg/dL</p>
        </div>
        @endif

        @if($latestVital->heart_rate)
        <div class="p-4 bg-pink-50 rounded-lg">
            <p class="text-xs text-pink-600 font-medium mb-1">Heart Rate</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->heart_rate }}</p>
            <p class="text-xs text-gray-500 mt-1">bpm</p>
        </div>
        @endif

        @if($latestVital->temperature)
        <div class="p-4 bg-orange-50 rounded-lg">
            <p class="text-xs text-orange-600 font-medium mb-1">Temperature</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->temperature }}</p>
            <p class="text-xs text-gray-500 mt-1">°C</p>
        </div>
        @endif

        @if($latestVital->spo2)
        <div class="p-4 bg-blue-50 rounded-lg">
            <p class="text-xs text-blue-600 font-medium mb-1">SpO2</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->spo2 }}%</p>
        </div>
        @endif

        @if($latestVital->weight)
        <div class="p-4 bg-green-50 rounded-lg">
            <p class="text-xs text-green-600 font-medium mb-1">Weight</p>
            <p class="text-2xl font-bold text-gray-800">{{ $latestVital->weight }}</p>
            <p class="text-xs text-gray-500 mt-1">kg</p>
        </div>
        @endif
    </div>

    <p class="text-xs text-gray-500 mt-4">Recorded {{ $latestVital->created_at->diffForHumans() }}</p>
</div>
@endif

<!-- Vitals History -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 font-sora">History</h3>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($vitals as $vital)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="font-semibold text-gray-800">{{ $vital->created_at->format('l, F j, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $vital->created_at->format('g:i A') }}</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                    {{ ucfirst($vital->source ?? 'manual') }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @if($vital->systolic && $vital->diastolic)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Blood Pressure</p>
                    <p class="font-semibold text-gray-800">{{ $vital->systolic }}/{{ $vital->diastolic }} mmHg</p>
                </div>
                @endif

                @if($vital->glucose_level)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Glucose</p>
                    <p class="font-semibold text-gray-800">{{ $vital->glucose_level }} mg/dL</p>
                </div>
                @endif

                @if($vital->heart_rate)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Heart Rate</p>
                    <p class="font-semibold text-gray-800">{{ $vital->heart_rate }} bpm</p>
                </div>
                @endif

                @if($vital->temperature)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Temperature</p>
                    <p class="font-semibold text-gray-800">{{ $vital->temperature }} °C</p>
                </div>
                @endif

                @if($vital->spo2)
                <div>
                    <p class="text-xs text-gray-500 mb-1">SpO2</p>
                    <p class="font-semibold text-gray-800">{{ $vital->spo2 }}%</p>
                </div>
                @endif

                @if($vital->weight)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Weight</p>
                    <p class="font-semibold text-gray-800">{{ $vital->weight }} kg</p>
                </div>
                @endif
            </div>

            @if($vital->notes)
            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">{{ $vital->notes }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-gray-500 text-lg">No vital signs recorded yet</p>
            <a href="{{ route('patient.vitals.create') }}" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                Log Your First Reading
            </a>
        </div>
        @endforelse
    </div>

    @if($vitals->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $vitals->links() }}
    </div>
    @endif
</div>
@endsection
