@extends('layouts.app')

@section('title', 'Log Vital Signs')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Log Vital Signs</h1>
    <p class="text-gray-600 mt-1">Record your health measurements</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ activeTab: 'blood_pressure' }">
    <!-- Tabs -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex gap-8 px-6" aria-label="Tabs">
                    <button @click="activeTab = 'blood_pressure'" :class="activeTab === 'blood_pressure' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Blood Pressure
                    </button>
                    <button @click="activeTab = 'glucose'" :class="activeTab === 'glucose' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Blood Glucose
                    </button>
                    <button @click="activeTab = 'temperature'" :class="activeTab === 'temperature' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Temperature
                    </button>
                    <button @click="activeTab = 'spo2'" :class="activeTab === 'spo2' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        SpO2
                    </button>
                    <button @click="activeTab = 'weight'" :class="activeTab === 'weight' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        Weight
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Blood Pressure Form -->
    <div x-show="activeTab === 'blood_pressure'" class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Record Blood Pressure</h3>
            
            <form method="POST" action="{{ route('patient.vitals.blood-pressure') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Systolic (mmHg)</label>
                        <input type="number" name="systolic" required min="50" max="300" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="120">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Diastolic (mmHg)</label>
                        <input type="number" name="diastolic" required min="30" max="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="80">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pulse (bpm)</label>
                        <input type="number" name="pulse" required min="30" max="250" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="72">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Save Blood Pressure
                </button>
            </form>
        </div>
    </div>

    <!-- Blood Glucose Form -->
    <div x-show="activeTab === 'glucose'" class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Record Blood Glucose</h3>
            
            <form method="POST" action="{{ route('patient.vitals.glucose') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Glucose Level</label>
                        <input type="number" name="glucose_value" required min="20" max="600" step="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                        <select name="glucose_unit" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="mg/dL">mg/dL</option>
                            <option value="mmol/L">mmol/L</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Context</label>
                    <select name="glucose_context" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="fasting_8hr">Fasting (8 hours)</option>
                        <option value="before_meal">Before Meal</option>
                        <option value="after_meal_2hr">After Meal (2 hours)</option>
                        <option value="bedtime">Bedtime</option>
                        <option value="random">Random</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Save Glucose Reading
                </button>
            </form>
        </div>
    </div>

    <!-- Temperature Form -->
    <div x-show="activeTab === 'temperature'" class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Record Temperature</h3>
            
            <form method="POST" action="{{ route('patient.vitals.temperature') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Temperature</label>
                        <input type="number" name="temperature" required min="30" max="45" step="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="36.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                        <select name="temperature_unit" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="C">Celsius (°C)</option>
                            <option value="F">Fahrenheit (°F)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <select name="temperature_location" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="oral">Oral</option>
                            <option value="armpit">Armpit</option>
                            <option value="forehead">Forehead</option>
                            <option value="ear">Ear</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Save Temperature
                </button>
            </form>
        </div>
    </div>

    <!-- SpO2 Form -->
    <div x-show="activeTab === 'spo2'" class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Record SpO2</h3>
            
            <form method="POST" action="{{ route('patient.vitals.spo2') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">SpO2 (%)</label>
                        <input type="number" name="spo2_value" required min="50" max="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="98">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pulse (bpm)</label>
                        <input type="number" name="pr_bpm" required min="30" max="250" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="72">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Save SpO2 Reading
                </button>
            </form>
        </div>
    </div>

    <!-- Weight Form -->
    <div x-show="activeTab === 'weight'" class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Record Weight</h3>
            
            <form method="POST" action="{{ route('patient.vitals.weight') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight" required min="10" max="500" step="0.1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="70.5">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="recorded_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                </div>

                @if(auth()->user()->patientProfile->height)
                <div class="p-4 bg-blue-50 rounded-lg mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>Your height:</strong> {{ auth()->user()->patientProfile->height }} cm<br>
                        BMI will be calculated automatically
                    </p>
                </div>
                @endif

                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
                    Save Weight
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
