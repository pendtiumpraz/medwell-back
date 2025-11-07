@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="flex h-screen overflow-hidden">
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-2xl">
            <div class="mb-4">
                <a href="{{ route('super-admin.departments.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Departments
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Edit Department: {{ $department->name }}</h1>
                <p class="text-gray-600 text-sm mt-1">Update department information</p>
            </div>
        </div>
    </div>

    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 z-10 shadow-lg">
            <h2 class="text-xl font-bold">Department Details</h2>
            <p class="text-white/80 text-xs mt-1">Update required fields</p>
        </div>

        <form action="{{ route('super-admin.departments.update', $department->id) }}" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Basic Information</h3>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Facility <span class="text-red-500">*</span>
                    </label>
                    <select name="facility_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('facility_id') border-red-500 @enderror">
                        <option value="">Select Facility</option>
                        @foreach($facilities as $fac)
                        <option value="{{ $fac->id }}" {{ old('facility_id', $department->facility_id) == $fac->id ? 'selected' : '' }}>
                            {{ $fac->name }} ({{ $fac->organization->name }})
                        </option>
                        @endforeach
                    </select>
                    @error('facility_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Department Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Department Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $department->code) }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('code') border-red-500 @enderror"
                        placeholder="e.g., DEPT001">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Head Name</label>
                    <input type="text" name="head_name" value="{{ old('head_name', $department->head_name) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="active" {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Contact Information</h3>
                
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $department->email) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $department->phone) }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Description</h3>
                
                <div class="mb-3">
                    <textarea name="description" rows="3" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $department->description) }}</textarea>
                </div>
            </div>

            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Update Department
                </button>
                <a href="{{ route('super-admin.departments.show', $department->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
