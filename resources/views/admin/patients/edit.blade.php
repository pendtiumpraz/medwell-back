@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark flex items-center gap-2 mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Patient Details
    </a>
    
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Edit Patient</h1>
    <p class="text-gray-600 mt-1">Update patient information for {{ $patient->full_name }}</p>
</div>

<form method="POST" action="{{ route('admin.patients.update', $patient->id) }}" class="bg-white rounded-xl shadow-sm p-8">
    @csrf
    @method('PUT')

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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
            <input type="email" name="email" value="{{ old('email', $patient->user->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
            <input type="text" value="{{ $patient->user->username }}" disabled class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
            <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-800 mb-6 font-sora pb-3 border-b">Personal Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
            <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
        <div class="grid grid-cols-3 gap-4">
            <label class="flex items-center justify-center px-4 py-3 border-2 {{ $patient->gender === 'male' ? 'border-primary bg-primary/5' : 'border-gray-300' }} rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="male" {{ $patient->gender === 'male' ? 'checked' : '' }} required class="mr-2">
                <span class="text-sm font-medium">Male</span>
            </label>
            <label class="flex items-center justify-center px-4 py-3 border-2 {{ $patient->gender === 'female' ? 'border-primary bg-primary/5' : 'border-gray-300' }} rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="female" {{ $patient->gender === 'female' ? 'checked' : '' }} required class="mr-2">
                <span class="text-sm font-medium">Female</span>
            </label>
            <label class="flex items-center justify-center px-4 py-3 border-2 {{ $patient->gender === 'other' ? 'border-primary bg-primary/5' : 'border-gray-300' }} rounded-lg cursor-pointer hover:border-primary transition-colors">
                <input type="radio" name="gender" value="other" {{ $patient->gender === 'other' ? 'checked' : '' }} required class="mr-2">
                <span class="text-sm font-medium">Other</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
            <input type="tel" name="phone" value="{{ old('phone', $patient->phone) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Type</label>
            <select name="blood_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="">Select Blood Type</option>
                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                    <option value="{{ $type }}" {{ $patient->blood_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Racial Origin</label>
        <input type="text" name="racial_origin" value="{{ old('racial_origin', $patient->racial_origin) }}" maxlength="50" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
    </div>

    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">{{ old('address', $patient->address) }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
            <input type="number" name="height" value="{{ old('height', $patient->height) }}" step="0.1" min="50" max="300" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
            <input type="number" name="weight" value="{{ old('weight', $patient->weight) }}" step="0.1" min="10" max="500" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <div class="flex gap-4">
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold shadow-lg">
            Update Patient
        </button>
        <a href="{{ route('admin.patients.show', $patient->id) }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
            Cancel
        </a>
    </div>
</form>
@endsection
