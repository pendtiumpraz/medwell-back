@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('super-admin.departments.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Departments
        </a>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">{{ $department->name }}</h1>
        <p class="text-gray-600 mt-1">{{ $department->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('super-admin.departments.edit', $department->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>
            Edit Department
        </a>
        <form action="{{ route('super-admin.departments.destroy', $department->id) }}" method="POST" class="inline-block">
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

<!-- Department Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Department Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Department Code</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->code }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Facility</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->facility->name }}</p>
                    <p class="text-xs text-gray-500">{{ $department->facility->organization->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Head Name</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->head_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $department->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($department->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->phone ?? 'N/A' }}</p>
                </div>

                <div class="col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->description ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Stats</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $department->users->count() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Created</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                    <p class="text-base font-medium text-gray-800">{{ $department->updated_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Department ID</p>
                    <p class="text-base font-medium text-gray-800">#{{ $department->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
