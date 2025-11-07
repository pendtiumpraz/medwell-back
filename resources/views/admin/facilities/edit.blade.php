@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-2xl">
            <div class="mb-4">
                <a href="{{ route('super-admin.facilities.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Facilities
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Edit Facility: {{ $facility->name }}</h1>
                <p class="text-gray-600 text-sm mt-1">Update facility information</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Facility Information</h2>
                <p class="text-sm text-gray-600">Update the facility details in the form on the right →</p>
                
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="font-medium">{{ $facility->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="font-medium">{{ $facility->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Form -->
    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 z-10 shadow-lg">
            <h2 class="text-xl font-bold">Facility Details</h2>
            <p class="text-white/80 text-xs mt-1">Update required fields</p>
        </div>

        <form action="{{ route('super-admin.facilities.update', $facility->id) }}" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-primary"></i>
                    Basic Information
                </h3>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Organization <span class="text-red-500">*</span>
                    </label>
                    <select name="organization_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('organization_id') border-red-500 @enderror">
                        <option value="">Select Organization</option>
                        @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ old('organization_id', $facility->organization_id) == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Facility Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $facility->name) }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                    <input type="text" name="type" value="{{ old('type', $facility->type) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="e.g., Hospital, Clinic, Pharmacy">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="active" {{ old('status', $facility->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $facility->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Contact & Address -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-phone mr-2 text-primary"></i>
                    Contact & Address
                </h3>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $facility->phone) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('address', $facility->address) }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Update Facility
                </button>
                <a href="{{ route('super-admin.facilities.show', $facility->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
