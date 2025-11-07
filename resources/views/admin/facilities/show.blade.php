@extends('layouts.app')

@section('title', 'Facility Details')

@section('content')
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('super-admin.facilities.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Facilities
        </a>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">{{ $facility->name }}</h1>
        <p class="text-gray-600 mt-1">{{ $facility->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('super-admin.facilities.edit', $facility->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>
            Edit Facility
        </a>
        <form action="{{ route('super-admin.facilities.destroy', $facility->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    onclick="return confirm('Are you sure you want to delete this facility?')">
                <i class="fas fa-trash mr-2"></i>
                Delete
            </button>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Organization</p>
                <p class="text-xl font-bold text-gray-800">{{ $facility->organization->name }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Departments</p>
                <p class="text-3xl font-bold text-gray-800">{{ $facility->departments->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sitemap text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Capacity</p>
                <p class="text-3xl font-bold text-gray-800">{{ $facility->capacity ?? 'N/A' }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-bed text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $facility->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $facility->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $facility->status === 'under_maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $facility->status)) }}
                </span>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Facility Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Facility Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Facility Code</p>
                    <p class="text-base font-medium text-gray-800">{{ $facility->code }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Type</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $facility->type === 'main' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $facility->type === 'branch' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $facility->type === 'outpatient' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $facility->type === 'emergency' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $facility->type === 'diagnostic' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $facility->type === 'laboratory' ? 'bg-indigo-100 text-indigo-800' : '' }}
                        {{ $facility->type === 'pharmacy' ? 'bg-pink-100 text-pink-800' : '' }}">
                        {{ ucfirst($facility->type) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-base font-medium text-gray-800">{{ $facility->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-base font-medium text-gray-800">{{ $facility->phone ?? 'N/A' }}</p>
                </div>

                <div class="col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Address</p>
                    <p class="text-base font-medium text-gray-800">
                        @if($facility->address)
                            {{ $facility->address }}<br>
                            {{ $facility->city }}, {{ $facility->state }} {{ $facility->postal_code }}
                        @else
                            N/A
                        @endif
                    </p>
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
                    <p class="text-base font-medium text-gray-800">{{ $facility->created_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $facility->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="text-base font-medium text-gray-800">{{ $facility->updated_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $facility->updated_at->diffForHumans() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Facility ID</p>
                    <p class="text-base font-medium text-gray-800">#{{ $facility->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Departments -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800 font-sora">Departments</h2>
        <a href="{{ route('super-admin.departments.index') }}?facility={{ $facility->id }}" class="text-primary hover:text-primary-dark text-sm">
            View All →
        </a>
    </div>
    
    @if($facility->departments->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($facility->departments as $dept)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-sitemap text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $dept->name }}</p>
                    <p class="text-xs text-gray-500">{{ $dept->code }}</p>
                </div>
            </div>
            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                {{ $dept->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($dept->status) }}
            </span>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-gray-500 text-center py-8">No departments yet</p>
    @endif
</div>
@endsection
