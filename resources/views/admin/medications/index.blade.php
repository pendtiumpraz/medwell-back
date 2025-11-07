@extends('layouts.app')

@section('title', 'Medications')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">Medications Master</h1>
        <p class="text-gray-600 mt-1">Manage medication database</p>
    </div>
    <a href="{{ route('super-admin.medications.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add Medication
    </a>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('super-admin.medications.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medications..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
        </div>
        <div class="min-w-[150px]">
            <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="">All Categories</option>
                <option value="antibiotic" {{ request('category') == 'antibiotic' ? 'selected' : '' }}>Antibiotic</option>
                <option value="analgesic" {{ request('category') == 'analgesic' ? 'selected' : '' }}>Analgesic</option>
                <option value="antiviral" {{ request('category') == 'antiviral' ? 'selected' : '' }}>Antiviral</option>
                <option value="vitamin" {{ request('category') == 'vitamin' ? 'selected' : '' }}>Vitamin</option>
            </select>
        </div>
        <div class="min-w-[130px]">
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('super-admin.medications.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
            Reset
        </a>
    </form>
</div>

<!-- Medications Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generic Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manufacturer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescription</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($medications as $med)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $med->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $med->generic_name ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $med->brand_name ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($med->category ?? 'General') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $med->manufacturer ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($med->requires_prescription)
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Required
                    </span>
                    @else
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Not Required
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $med->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($med->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('super-admin.medications.show', $med->id) }}" class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200">
                        <i class="fas fa-eye text-sm"></i>
                        <span class="ml-1 text-xs font-medium">View</span>
                    </a>
                    <a href="{{ route('super-admin.medications.edit', $med->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                        <i class="fas fa-edit text-sm"></i>
                        <span class="ml-1 text-xs font-medium">Edit</span>
                    </a>
                    <form action="{{ route('super-admin.medications.destroy', $med->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash text-sm"></i>
                            <span class="ml-1 text-xs font-medium">Delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-pills text-4xl mb-2"></i>
                    <p>No medications found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $medications->links() }}
</div>
@endsection
