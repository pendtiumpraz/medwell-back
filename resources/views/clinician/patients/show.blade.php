@extends('layouts.app')

@section('title', 'Patient Detail')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('clinician.patients.index') }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patients
    </a>
</div>

<!-- Patient Header -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center gap-6">
        <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white text-2xl font-bold">
            {{ substr($patient->full_name, 0, 2) }}
        </div>

        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-800 font-sora">{{ $patient->full_name }}</h1>
            <p class="text-gray-600 mt-1">{{ $patient->age }} years • {{ ucfirst($patient->gender) }} • {{ $patient->blood_type ?? 'N/A' }}</p>
            
            <div class="flex items-center gap-4 mt-3">
                @if($patient->wearable_type !== 'none')
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ ucfirst($patient->wearable_type) }}
                </span>
                @endif
                
                <span class="text-sm text-gray-500">
                    📧 {{ $patient->user->email }} • 📱 {{ $patient->phone }}
                </span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('clinician.vitals.index', $patient->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                Add Vitals
            </a>
            <a href="{{ route('clinician.medications.create', $patient->id) }}" class="px-4 py-2 border-2 border-primary text-primary rounded-lg hover:bg-primary/5">
                Prescribe Medication
            </a>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Vitals & Alerts -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Latest Vitals -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Latest Vital Signs</h3>
            
            @if($latestVital = $patient->vitalSigns->first())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                @if($latestVital->systolic)
                <div class="p-3 bg-red-50 rounded-lg">
                    <p class="text-xs text-red-600 mb-1">Blood Pressure</p>
                    <p class="text-xl font-bold">{{ $latestVital->systolic }}/{{ $latestVital->diastolic }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $latestVital->recorded_at->diffForHumans() }}</p>
                </div>
                @endif

                @if($latestVital->glucose_value)
                <div class="p-3 bg-purple-50 rounded-lg">
                    <p class="text-xs text-purple-600 mb-1">Glucose</p>
                    <p class="text-xl font-bold">{{ $latestVital->glucose_value }}</p>
                    <p class="text-xs text-gray-500 mt-1">mg/dL</p>
                </div>
                @endif

                @if($latestVital->heart_rate)
                <div class="p-3 bg-pink-50 rounded-lg">
                    <p class="text-xs text-pink-600 mb-1">Heart Rate</p>
                    <p class="text-xl font-bold">{{ $latestVital->heart_rate }}</p>
                    <p class="text-xs text-gray-500 mt-1">bpm</p>
                </div>
                @endif

                @if($latestVital->spo2)
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-600 mb-1">SpO2</p>
                    <p class="text-xl font-bold">{{ $latestVital->spo2 }}%</p>
                </div>
                @endif
            </div>
            @else
            <p class="text-center text-gray-500 py-8">No vitals recorded yet</p>
            @endif

            <a href="{{ route('clinician.vitals.index', $patient->id) }}" class="text-primary hover:text-primary-dark text-sm font-medium">
                View All Vitals →
            </a>
        </div>

        <!-- Unresolved Alerts -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Unresolved Health Alerts</h3>
            
            <div class="space-y-3">
                @forelse($patient->healthAlerts()->unresolved()->latest()->limit(5)->get() as $alert)
                <div class="flex items-start gap-3 p-4 border-l-4 {{ $alert->priority === 'critical' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50' }} rounded-lg">
                    <svg class="w-5 h-5 {{ $alert->priority === 'critical' ? 'text-red-600' : 'text-yellow-600' }} mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $alert->title }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $alert->message }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$alert->acknowledged_at)
                    <form method="POST" action="{{ route('clinician.alerts.acknowledge', $alert->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                            Acknowledge
                        </button>
                    </form>
                    @endif
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No unresolved alerts</p>
                @endforelse
            </div>
        </div>

        <!-- Active Medications -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 font-sora">Active Medications</h3>
                <a href="{{ route('clinician.medications.create', $patient->id) }}" class="text-primary hover:text-primary-dark text-sm font-medium">
                    + Prescribe New
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($patient->medications()->where('status', 'active')->get() as $med)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $med->medication->name }}</p>
                        <p class="text-sm text-gray-600">{{ $med->dosage }} - {{ $med->frequency }}</p>
                        <span class="inline-block mt-2 px-2 py-0.5 text-xs font-semibold rounded-full
                            {{ $med->consent_status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $med->consent_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $med->consent_status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($med->consent_status) }}
                        </span>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <p>{{ $med->start_date->format('M d, Y') }}</p>
                        @if($med->end_date)
                        <p>to {{ $med->end_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No active medications</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column - Patient Info -->
    <div class="space-y-6">
        <!-- Personal Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Personal Information</h3>
            
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Age</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->age }} years</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Gender</dt>
                    <dd class="font-semibold text-gray-800 capitalize">{{ $patient->gender }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Blood Type</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->blood_type ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Height</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->height ?? 'N/A' }} cm</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Weight</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->weight ?? 'N/A' }} kg</dd>
                </div>
                @if($patient->height && $patient->weight)
                <div>
                    <dt class="text-gray-500">BMI</dt>
                    <dd class="font-semibold text-gray-800">{{ round($patient->getCurrentBMI(), 1) }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Medical History -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Medical History</h3>
            
            <div class="space-y-4">
                <!-- Conditions -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Conditions</p>
                    @forelse($patient->medicalConditions as $condition)
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full mr-2 mb-2">
                        {{ $condition->condition_name }}
                    </span>
                    @empty
                    <p class="text-sm text-gray-500">None</p>
                    @endforelse
                </div>

                <!-- Allergies -->
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Allergies</p>
                    @forelse($patient->allergies as $allergy)
                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full mr-2 mb-2">
                        {{ $allergy->allergen }}
                    </span>
                    @empty
                    <p class="text-sm text-gray-500">None</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Contact</h3>
            
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->phone }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Address</dt>
                    <dd class="font-semibold text-gray-800">{{ $patient->address ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
