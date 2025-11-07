@extends('layouts.app')

@section('title', 'Medication Details')

@section('content')
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('super-admin.medications.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Medications
        </a>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">{{ $medication->name }}</h1>
        <p class="text-gray-600 mt-1">
            {{ $medication->generic_name ?? 'Generic Name N/A' }}
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('super-admin.medications.edit', $medication->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>
            Edit Medication
        </a>
        <form action="{{ route('super-admin.medications.destroy', $medication->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    onclick="return confirm('Are you sure?')">
                <i class="fas fa-trash mr-2"></i>
                Delete
            </button>
        </form>
    </div>
</div>

<!-- Medication Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Medication Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Name</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Generic Name</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->generic_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Brand Name</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->brand_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Category</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($medication->category ?? 'General') }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Manufacturer</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->manufacturer ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Route</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->route ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Prescription Requirement</p>
                    @if($medication->requires_prescription)
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-prescription mr-1"></i> Required
                    </span>
                    @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i> Not Required
                    </span>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $medication->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($medication->status) }}
                    </span>
                </div>

                <div class="col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->description ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Stats</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Created</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $medication->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="text-base font-medium text-gray-800">{{ $medication->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $medication->updated_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Medication ID</p>
                    <p class="text-base font-medium text-gray-800">#{{ $medication->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
