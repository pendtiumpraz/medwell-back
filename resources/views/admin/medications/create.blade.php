@extends('layouts.app')

@section('title', 'Create Medication')

@section('content')
<div class="flex h-screen overflow-hidden">
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-2xl">
            <div class="mb-4">
                <a href="{{ route('super-admin.medications.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Medications
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Add New Medication</h1>
                <p class="text-gray-600 text-sm mt-1">Add medication to master database</p>
            </div>
        </div>
    </div>

    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-primary to-primary-dark text-white p-4 z-10 shadow-lg">
            <h2 class="text-xl font-bold">Medication Details</h2>
            <p class="text-white/80 text-xs mt-1">Complete all required fields</p>
        </div>

        <form action="{{ route('super-admin.medications.store') }}" method="POST" class="p-4 space-y-4">
            @csrf

            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Basic Information</h3>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Medication Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Generic Name</label>
                    <input type="text" name="generic_name" value="{{ old('generic_name') }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Brand Name</label>
                    <input type="text" name="brand_name" value="{{ old('brand_name') }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="e.g., Antibiotic, Analgesic">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Manufacturer</label>
                    <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Route</label>
                    <input type="text" name="route" value="{{ old('route') }}" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="e.g., Oral, IV, Topical">
                </div>

                <div class="mb-3">
                    <label class="flex items-center text-sm text-gray-700">
                        <input type="checkbox" name="requires_prescription" value="1" {{ old('requires_prescription') ? 'checked' : '' }} 
                            class="mr-2 rounded border-gray-300">
                        Requires Prescription
                    </label>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Description</h3>
                
                <div class="mb-3">
                    <textarea name="description" rows="3" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Medication description...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Create Medication
                </button>
                <a href="{{ route('super-admin.medications.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
