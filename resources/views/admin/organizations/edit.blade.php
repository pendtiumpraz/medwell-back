@extends('layouts.app')

@section('title', 'Edit Organization')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-2xl">
            <div class="mb-4">
                <a href="{{ route('super-admin.organizations.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Organizations
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Edit Organization: {{ $organization->name }}</h1>
                <p class="text-gray-600 text-sm mt-1">Update organization information</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Organization Information</h2>
                <p class="text-sm text-gray-600">Update the organization details in the form on the right →</p>
                
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="font-medium">{{ $organization->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="font-medium">{{ $organization->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Form -->
    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 z-10 shadow-lg">
            <h2 class="text-xl font-bold">Organization Details</h2>
            <p class="text-white/80 text-xs mt-1">Update required fields</p>
        </div>

        <form action="{{ route('super-admin.organizations.update', $organization->id) }}" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')
            @csrf

            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Basic Information</h3>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Organization Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $organization->name) }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Organization Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $organization->code) }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('code') border-red-500 @enderror"
                        placeholder="e.g., ORG001">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('type') border-red-500 @enderror">
                        <option value="">Select Type</option>
                        <option value="hospital" {{ old('type', $organization->type) == 'hospital' ? 'selected' : '' }}>Hospital</option>
                        <option value="clinic" {{ old('type', $organization->type) == 'clinic' ? 'selected' : '' }}>Clinic</option>
                        <option value="pharmacy" {{ old('type', $organization->type) == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                        <option value="laboratory" {{ old('type', $organization->type) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="other" {{ old('type', $organization->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="active" {{ old('status', $organization->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $organization->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $organization->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Contact Information</h3>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $organization->email) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $organization->website) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('website') border-red-500 @enderror"
                        placeholder="https://example.com">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Address</h3>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Street Address</label>
                    <textarea name="address" rows="2" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('address', $organization->address) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $organization->city) }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', $organization->state) }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', $organization->country) }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $organization->postal_code) }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Update Organization
                </button>
                <a href="{{ route('super-admin.organizations.show', $organization->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
