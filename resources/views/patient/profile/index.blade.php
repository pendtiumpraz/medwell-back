@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">My Profile</h1>
    <p class="text-gray-600 mt-1">Manage your personal information</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Avatar -->
            <div class="text-center mb-6">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover mb-4">
                @else
                    <div class="w-32 h-32 bg-gradient-to-br from-primary to-primary-dark rounded-full mx-auto flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ substr(auth()->user()->patientProfile->full_name, 0, 2) }}
                    </div>
                @endif
                
                <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->patientProfile->full_name }}</h3>
                <p class="text-gray-500">{{ '@' . auth()->user()->username }}</p>
            </div>

            <!-- Upload Avatar -->
            <form method="POST" action="{{ route('patient.profile.upload-avatar') }}" enctype="multipart/form-data" class="mb-6">
                @csrf
                <label class="block">
                    <span class="sr-only">Choose avatar</span>
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary file:text-white
                        hover:file:bg-primary-dark
                    "/>
                </label>
                <button type="submit" class="w-full mt-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                    Upload Photo
                </button>
            </form>

            <!-- Quick Stats -->
            <div class="space-y-3 pt-6 border-t border-gray-200">
                <div class="flex justify-between">
                    <span class="text-gray-600">Age</span>
                    <span class="font-semibold">{{ auth()->user()->patientProfile->age }} years</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Blood Type</span>
                    <span class="font-semibold">{{ auth()->user()->patientProfile->blood_type ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Height</span>
                    <span class="font-semibold">{{ auth()->user()->patientProfile->height ?? 'N/A' }} cm</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Weight</span>
                    <span class="font-semibold">{{ auth()->user()->patientProfile->weight ?? 'N/A' }} kg</span>
                </div>
                @if(auth()->user()->patientProfile->height && auth()->user()->patientProfile->weight)
                <div class="flex justify-between">
                    <span class="text-gray-600">BMI</span>
                    <span class="font-semibold">{{ round(auth()->user()->patientProfile->getCurrentBMI(), 1) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 font-sora">Personal Information</h3>
            
            <form method="POST" action="{{ route('patient.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ auth()->user()->patientProfile->full_name }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ auth()->user()->email }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            value="{{ auth()->user()->patientProfile->phone }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                        <input 
                            type="date" 
                            name="date_of_birth" 
                            value="{{ auth()->user()->patientProfile->date_of_birth }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="male" {{ auth()->user()->patientProfile->gender === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ auth()->user()->patientProfile->gender === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ auth()->user()->patientProfile->gender === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Type</label>
                        <select name="blood_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">Select Blood Type</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                <option value="{{ $type }}" {{ auth()->user()->patientProfile->blood_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Height -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                        <input 
                            type="number" 
                            name="height" 
                            step="0.1"
                            value="{{ auth()->user()->patientProfile->height }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                        <input 
                            type="number" 
                            name="weight" 
                            step="0.1"
                            value="{{ auth()->user()->patientProfile->weight }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                    <textarea 
                        name="address" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                    >{{ auth()->user()->patientProfile->address }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex gap-3">
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold"
                    >
                        Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 font-sora">Change Password</h3>
            
            <form method="POST" action="{{ route('patient.profile.change-password') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                        >
                    </div>
                </div>

                <button 
                    type="submit"
                    class="mt-6 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold"
                >
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
