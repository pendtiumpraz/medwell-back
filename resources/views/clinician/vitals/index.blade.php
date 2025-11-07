@extends('layouts.app')

@section('title', 'Patient Vitals')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Vital Signs - {{ $patient->full_name }}</h1>
            <p class="text-gray-600 mt-1">Review and add vital signs for this patient</p>
        </div>
        <a href="{{ route('clinician.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
            ← Back to Patient
        </a>
    </div>
</div>

<!-- Filter & Add Form Toggle -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6" x-data="{ showForm: false }">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-gray-800 font-sora">Filters</h3>
        <button @click="showForm = !showForm" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
            <span x-show="!showForm">+ Add Vital Signs</span>
            <span x-show="showForm">✕ Close Form</span>
        </button>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Types</option>
                <option value="blood_pressure">Blood Pressure</option>
                <option value="glucose">Glucose</option>
                <option value="temperature">Temperature</option>
                <option value="spo2">SpO2</option>
                <option value="weight">Weight</option>
            </select>
        </div>
        <div>
            <input type="date" name="from" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="From Date">
        </div>
        <div>
            <input type="date" name="to" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="To Date">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Apply Filters
        </button>
    </form>

    <!-- Add Vitals Form -->
    <div x-show="showForm" class="border-t border-gray-200 pt-6 mt-4">
        <form method="POST" action="{{ route('clinician.vitals.store', $patient->id) }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Blood Pressure -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Blood Pressure</p>
                    <div class="space-y-2">
                        <input type="number" name="systolic" placeholder="Systolic" min="50" max="300" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <input type="number" name="diastolic" placeholder="Diastolic" min="30" max="200" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <input type="number" name="pulse" placeholder="Pulse (bpm)" min="30" max="250" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                </div>

                <!-- Glucose -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Blood Glucose</p>
                    <div class="space-y-2">
                        <input type="number" name="glucose_value" placeholder="Glucose Level" step="0.1" min="20" max="600" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <select name="glucose_context" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="">Context</option>
                            <option value="fasting_8hr">Fasting</option>
                            <option value="before_meal">Before Meal</option>
                            <option value="after_meal_2hr">After Meal</option>
                            <option value="random">Random</option>
                        </select>
                    </div>
                </div>

                <!-- Temperature -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Temperature</p>
                    <div class="space-y-2">
                        <input type="number" name="temperature" placeholder="Temperature" step="0.1" min="30" max="45" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <select name="temperature_unit" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="C">Celsius</option>
                            <option value="F">Fahrenheit</option>
                        </select>
                    </div>
                </div>

                <!-- SpO2 -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">SpO2</p>
                    <div class="space-y-2">
                        <input type="number" name="spo2_value" placeholder="SpO2 (%)" min="50" max="100" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <input type="number" name="pr_bpm" placeholder="PR (bpm)" min="30" max="250" class="w-full px-3 py-2 border rounded-lg text-sm">
                    </div>
                </div>

                <!-- Weight -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Weight</p>
                    <input type="number" name="weight" placeholder="Weight (kg)" step="0.1" min="10" max="500" class="w-full px-3 py-2 border rounded-lg text-sm">
                </div>

                <!-- Recorded At -->
                <div class="p-4 border-2 border-gray-200 rounded-lg">
                    <p class="font-semibold text-gray-800 mb-3">Date & Time</p>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-3 py-2 border rounded-lg text-sm">
                </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                Save Vital Signs
            </button>
        </form>
    </div>
</div>

<!-- Vitals History -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800 font-sora">Vitals History</h3>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($vitals as $vital)
        <div class="p-6 hover:bg-gray-50">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="font-semibold text-gray-800">{{ $vital->recorded_at->format('l, F j, Y - g:i A') }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        {{ ucfirst($vital->source) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @if($vital->systolic && $vital->diastolic)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Blood Pressure</p>
                    <p class="font-semibold text-gray-800">{{ $vital->systolic }}/{{ $vital->diastolic }}</p>
                    @if($vital->pulse)
                    <p class="text-xs text-gray-500">Pulse: {{ $vital->pulse }} bpm</p>
                    @endif
                </div>
                @endif

                @if($vital->glucose_value)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Glucose</p>
                    <p class="font-semibold text-gray-800">{{ $vital->glucose_value }} mg/dL</p>
                    @if($vital->glucose_context)
                    <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $vital->glucose_context)) }}</p>
                    @endif
                </div>
                @endif

                @if($vital->temperature)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Temperature</p>
                    <p class="font-semibold text-gray-800">{{ $vital->temperature }}°{{ $vital->temperature_unit }}</p>
                </div>
                @endif

                @if($vital->spo2_value)
                <div>
                    <p class="text-xs text-gray-500 mb-1">SpO2</p>
                    <p class="font-semibold text-gray-800">{{ $vital->spo2_value }}%</p>
                </div>
                @endif

                @if($vital->weight)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Weight</p>
                    <p class="font-semibold text-gray-800">{{ $vital->weight }} kg</p>
                    @if($vital->bmi)
                    <p class="text-xs text-gray-500">BMI: {{ $vital->bmi }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-gray-500">No vitals recorded yet</p>
        </div>
        @endforelse
    </div>

    @if($vitals->hasPages())
    <div class="p-6 border-t">
        {{ $vitals->links() }}
    </div>
    @endif
</div>
@endsection
