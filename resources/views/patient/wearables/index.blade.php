@extends('layouts.app')

@section('title', 'Wearable Devices')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Wearable Devices</h1>
    <p class="text-gray-600 mt-1">Connect and sync your health devices</p>
</div>

<!-- Current Connection Status -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Current Connection</h3>
    
    @if(auth()->user()->patientProfile->wearable_type !== 'none')
    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-800 capitalize">{{ str_replace('_', ' ', auth()->user()->patientProfile->wearable_type) }} Connected</p>
                <p class="text-sm text-gray-600">Last sync: {{ auth()->user()->patientProfile->wearable_last_sync ? auth()->user()->patientProfile->wearable_last_sync->diffForHumans() : 'Never' }}</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <form method="POST" action="{{ route('patient.wearables.sync') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                    Sync Now
                </button>
            </form>
            <form method="POST" action="{{ route('patient.wearables.disconnect') }}" onsubmit="return confirm('Disconnect this device?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border-2 border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                    Disconnect
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="text-center py-8">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 mb-4">No device connected yet</p>
        <p class="text-sm text-gray-400">Connect a wearable device below to start tracking automatically</p>
    </div>
    @endif
</div>

<!-- Available Devices -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Fitbit -->
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Fitbit</h3>
                    <p class="text-sm text-gray-500">Track steps, heart rate, sleep</p>
                </div>
            </div>
        </div>
        
        <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Steps & distance
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Heart rate monitoring
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Sleep tracking
            </li>
        </ul>

        @if(auth()->user()->patientProfile->wearable_type === 'fitbit')
        <button disabled class="w-full px-4 py-3 bg-green-100 text-green-800 rounded-lg font-semibold">
            ✓ Connected
        </button>
        @else
        <a href="{{ route('patient.wearables.connect', 'fitbit') }}" class="block w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold text-center">
            Connect Fitbit
        </a>
        @endif
    </div>

    <!-- Huawei Health -->
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.5 6c-2.61.7-5.67 1-8.5 1s-5.89-.3-8.5-1L3 8c1.86.5 4 .83 6 1v13h2v-6h2v6h2V9c2-.17 4.14-.5 6-1l-.5-2zM12 6c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Huawei Health</h3>
                    <p class="text-sm text-gray-500">Connect Huawei watches</p>
                </div>
            </div>
        </div>
        
        <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Activity tracking
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Heart rate & SpO2
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Sleep analysis
            </li>
        </ul>

        @if(auth()->user()->patientProfile->wearable_type === 'huawei')
        <button disabled class="w-full px-4 py-3 bg-green-100 text-green-800 rounded-lg font-semibold">
            ✓ Connected
        </button>
        @else
        <a href="{{ route('patient.wearables.connect', 'huawei') }}" class="block w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold text-center">
            Connect Huawei
        </a>
        @endif
    </div>

    <!-- Apple Health -->
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-900 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Apple Health</h3>
                    <p class="text-sm text-gray-500">iPhone & Apple Watch</p>
                </div>
            </div>
        </div>
        
        <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                All health metrics
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                ECG & Blood oxygen
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Workout tracking
            </li>
        </ul>

        @if(auth()->user()->patientProfile->wearable_type === 'apple')
        <button disabled class="w-full px-4 py-3 bg-green-100 text-green-800 rounded-lg font-semibold">
            ✓ Connected
        </button>
        @else
        <a href="{{ route('patient.wearables.connect', 'apple') }}" class="block w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold text-center">
            Connect Apple Health
        </a>
        @endif
    </div>

    <!-- Samsung Health -->
    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-4 0-7-3-7-7V8.3l7-3.11 7 3.11V13c0 4-3 7-7 7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Samsung Health</h3>
                    <p class="text-sm text-gray-500">Galaxy Watch & phones</p>
                </div>
            </div>
        </div>
        
        <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Complete health data
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Body composition
            </li>
            <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Stress & sleep
            </li>
        </ul>

        @if(auth()->user()->patientProfile->wearable_type === 'samsung')
        <button disabled class="w-full px-4 py-3 bg-green-100 text-green-800 rounded-lg font-semibold">
            ✓ Connected
        </button>
        @else
        <a href="{{ route('patient.wearables.connect', 'samsung') }}" class="block w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold text-center">
            Connect Samsung Health
        </a>
        @endif
    </div>
</div>

<!-- Instructions -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <h4 class="font-bold text-blue-900 mb-2">How to Connect</h4>
    <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
        <li>Click "Connect" button on your preferred device</li>
        <li>Sign in to your wearable account (Fitbit, Huawei, etc.)</li>
        <li>Authorize Medwell to access your health data</li>
        <li>Your data will automatically sync every 15 minutes</li>
    </ol>
</div>
@endsection
