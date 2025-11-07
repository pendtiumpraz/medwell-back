@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl p-8 text-white mb-6 shadow-xl">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->username }}! 👋</h1>
        <p class="text-purple-100">Patient Dashboard - Your Health Overview</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Latest Weight</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">--</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-weight text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Blood Pressure</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">--</h3>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-heartbeat text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Active Medications</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">0</h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-pills text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Next Appointment</p>
                    <h3 class="text-lg font-bold text-gray-800 mt-2">None</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-calendar text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('patient.vitals.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-heartbeat text-blue-600"></i>
                    <span class="font-medium text-gray-700">View My Vital Signs</span>
                </a>
                <a href="{{ route('patient.medications.index') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-pills text-green-600"></i>
                    <span class="font-medium text-gray-700">My Medications</span>
                </a>
                <a href="{{ route('patient.wearables.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <i class="fas fa-watch text-purple-600"></i>
                    <span class="font-medium text-gray-700">Wearable Data</span>
                </a>
                <a href="{{ route('patient.profile.index') }}" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-user-circle text-gray-600"></i>
                    <span class="font-medium text-gray-700">My Profile</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Health Tips</h2>
            <div class="space-y-3">
                <div class="p-3 bg-green-50 rounded-lg">
                    <p class="text-sm font-medium text-green-800">💧 Stay Hydrated</p>
                    <p class="text-xs text-green-600 mt-1">Drink at least 8 glasses of water daily</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-800">🏃 Regular Exercise</p>
                    <p class="text-xs text-blue-600 mt-1">30 minutes of activity per day</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <p class="text-sm font-medium text-purple-800">😴 Quality Sleep</p>
                    <p class="text-xs text-purple-600 mt-1">Get 7-9 hours of sleep each night</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
