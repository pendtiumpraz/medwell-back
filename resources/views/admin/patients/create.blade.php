@extends('layouts.app')

@section('title', 'Add New Patient')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.patients.index') }}" class="text-primary hover:text-primary-dark flex items-center gap-2 mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patients
    </a>
    
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Add New Patient</h1>
    <p class="text-gray-600 mt-1">Create a new patient account</p>
</div>

<form method="POST" action="{{ route('admin.patients.store') }}" class="bg-white rounded-xl shadow-sm p-8">
    @csrf

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="text-lg font-bold text-gray-800 mb-6 font-sora pb-3 border-b">Account Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
            <input type="text" name="username" value="{{ old('username') }}" required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary @error('username') border-red-500 @enderror">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
            <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-800 mb-6 font-sora pb-3 border-b">Personal Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
        <div class="grid grid-cols-3 gap-4">
            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="male" required class="mr-2">
                <span class="text-sm font-medium">Male</span>
            </label>
            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="female" required class="mr-2">
                <span class="text-sm font-medium">Female</span>
            </label>
            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="other" required class="mr-2">
                <span class="text-sm font-medium">Other</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Type</label>
            <select name="blood_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Racial Origin</label>
        <input type="text" name="racial_origin" value="{{ old('racial_origin') }}" maxlength="50" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">{{ old('address') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
            <input type="number" name="height" value="{{ old('height') }}" step="0.1" min="50" max="300" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
            <input type="number" name="weight" value="{{ old('weight') }}" step="0.1" min="10" max="500" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <div class="flex gap-4 sticky bottom-0 bg-white pt-4 pb-2 border-t">
        <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold shadow-lg transition-all">
            <i class="fas fa-save mr-2"></i> Create Patient
        </button>
        <a href="{{ route('admin.patients.index') }}" class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold text-center transition-all">
            <i class="fas fa-times mr-2"></i> Cancel
        </a>
    </div>
</form>
@endsection
