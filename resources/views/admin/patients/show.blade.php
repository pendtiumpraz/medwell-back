@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('admin.patients.index') }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patients
    </a>
</div>

<!-- Patient Header -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-6">
            <!-- Avatar -->
            <div class="w-24 h-24 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white text-2xl font-bold">
                {{ substr($patient->full_name, 0, 2) }}
            </div>

            <!-- Patient Info -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800 font-sora">{{ $patient->full_name }}</h1>
                <p class="text-gray-600 mt-1">{{ '@' . $patient->user->username }}</p>
                
                <div class="flex items-center gap-4 mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $patient->onboarding_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $patient->onboarding_completed ? 'Active' : 'Onboarding' }}
                    </span>
                    
                    @if($patient->wearable_type !== 'none')
                    <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ ucfirst($patient->wearable_type) }} Connected
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                Edit Patient
            </button>
            <button class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Send Message
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $patient->vitalSigns->count() }}</p>
        <p class="text-sm text-gray-500">Total Vitals Logged</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $patient->medications->count() }}</p>
        <p class="text-sm text-gray-500">Active Medications</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $patient->healthAlerts->where('resolved_at', null)->count() }}</p>
        <p class="text-sm text-gray-500">Unresolved Alerts</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $patient->assignedClinicians->count() }}</p>
        <p class="text-sm text-gray-500">Assigned Clinicians</p>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Patient Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Personal Information</h3>
            
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-500">Age</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->age }} years</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Gender</dt>
                    <dd class="text-sm font-semibold text-gray-800 capitalize">{{ $patient->gender }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Phone</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->phone }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Blood Type</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->blood_type ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Height</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->height ?? 'N/A' }} cm</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Weight</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->weight ?? 'N/A' }} kg</dd>
                </div>
                @if($patient->height && $patient->weight)
                <div>
                    <dt class="text-sm text-gray-500">BMI</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ round($patient->getCurrentBMI(), 1) }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm text-gray-500">Address</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $patient->address ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Assigned Clinicians -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 font-sora">Assigned Clinicians</h3>
                <button class="text-primary hover:text-primary-dark text-sm font-medium">+ Add</button>
            </div>
            
            <div class="space-y-3">
                @forelse($patient->assignedClinicians as $clinician)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center text-teal-600 font-semibold text-sm">
                            {{ substr($clinician->username, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $clinician->username }}</p>
                            <p class="text-xs text-gray-500">{{ $clinician->email }}</p>
                        </div>
                    </div>
                    <button class="text-red-600 hover:text-red-800 text-xs">Remove</button>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No clinicians assigned</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column - Health Data -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Latest Vitals -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Latest Vital Signs</h3>
            
            @if($latestVital = $patient->vitalSigns->first())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($latestVital->systolic && $latestVital->diastolic)
                <div class="p-4 bg-red-50 rounded-lg">
                    <p class="text-xs text-red-600 font-medium mb-1">Blood Pressure</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->systolic }}/{{ $latestVital->diastolic }}</p>
                    <p class="text-xs text-gray-500 mt-1">mmHg</p>
                </div>
                @endif

                @if($latestVital->glucose_level)
                <div class="p-4 bg-purple-50 rounded-lg">
                    <p class="text-xs text-purple-600 font-medium mb-1">Glucose</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->glucose_level }}</p>
                    <p class="text-xs text-gray-500 mt-1">mg/dL</p>
                </div>
                @endif

                @if($latestVital->heart_rate)
                <div class="p-4 bg-pink-50 rounded-lg">
                    <p class="text-xs text-pink-600 font-medium mb-1">Heart Rate</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->heart_rate }}</p>
                    <p class="text-xs text-gray-500 mt-1">bpm</p>
                </div>
                @endif

                @if($latestVital->temperature)
                <div class="p-4 bg-orange-50 rounded-lg">
                    <p class="text-xs text-orange-600 font-medium mb-1">Temperature</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->temperature }}</p>
                    <p class="text-xs text-gray-500 mt-1">°C</p>
                </div>
                @endif

                @if($latestVital->spo2)
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-600 font-medium mb-1">SpO2</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->spo2 }}%</p>
                </div>
                @endif

                @if($latestVital->weight)
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="text-xs text-green-600 font-medium mb-1">Weight</p>
                    <p class="text-xl font-bold text-gray-800">{{ $latestVital->weight }}</p>
                    <p class="text-xs text-gray-500 mt-1">kg</p>
                </div>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-4">Last updated: {{ $latestVital->created_at->diffForHumans() }}</p>
            @else
            <p class="text-center text-gray-500 py-8">No vital signs recorded yet</p>
            @endif
        </div>

        <!-- Recent Health Alerts -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Recent Health Alerts</h3>
            
            <div class="space-y-3">
                @forelse($patient->healthAlerts()->latest()->limit(5)->get() as $alert)
                <div class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg">
                    <div class="w-10 h-10 bg-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-gray-800">{{ $alert->title }}</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-100 text-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-800">
                                {{ ucfirst($alert->priority) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $alert->message }}</p>
                        <p class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$alert->resolved_at)
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">Unresolved</span>
                    @else
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Resolved</span>
                    @endif
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No health alerts</p>
                @endforelse
            </div>
        </div>

        <!-- Medical History -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Medical Conditions & Allergies</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Medical Conditions -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Medical Conditions</h4>
                    <div class="space-y-2">
                        @forelse($patient->medicalConditions as $condition)
                        <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg">
                            <span class="text-sm text-gray-800">{{ $condition->condition_name }}</span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">None reported</p>
                        @endforelse
                    </div>
                </div>

                <!-- Allergies -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Allergies</h4>
                    <div class="space-y-2">
                        @forelse($patient->allergies as $allergy)
                        <div class="flex items-center gap-2 p-2 bg-red-50 rounded-lg">
                            <span class="text-sm text-gray-800">{{ $allergy->allergen }}</span>
                            @if($allergy->severity)
                            <span class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                {{ ucfirst($allergy->severity) }}
                            </span>
                            @endif
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">None reported</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
