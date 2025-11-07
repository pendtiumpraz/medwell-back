@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">Facilities Management</h1>
        <p class="text-gray-600 mt-1">Manage healthcare facilities across organizations</p>
    </div>
    <a href="{{ route('super-admin.facilities.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Add Facility
    </a>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('super-admin.facilities.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search facilities..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
        </div>
        <div class="min-w-[180px]">
            <select name="organization_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="">All Organizations</option>
                @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[150px]">
            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="">All Types</option>
                <option value="main" {{ request('type') == 'main' ? 'selected' : '' }}>Main</option>
                <option value="branch" {{ request('type') == 'branch' ? 'selected' : '' }}>Branch</option>
                <option value="outpatient" {{ request('type') == 'outpatient' ? 'selected' : '' }}>Outpatient</option>
                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                <option value="diagnostic" {{ request('type') == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                <option value="pharmacy" {{ request('type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
            </select>
        </div>
        <div class="min-w-[130px]">
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('super-admin.facilities.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
            Reset
        </a>
    </form>
</div>

<!-- Facilities Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($facilities as $facility)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $facility->name }}</div>
                    <div class="text-sm text-gray-500">{{ $facility->code }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $facility->organization->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $facility->type === 'main' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $facility->type === 'branch' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $facility->type === 'outpatient' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $facility->type === 'emergency' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $facility->type === 'diagnostic' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $facility->type === 'laboratory' ? 'bg-indigo-100 text-indigo-800' : '' }}
                        {{ $facility->type === 'pharmacy' ? 'bg-pink-100 text-pink-800' : '' }}">
                        {{ ucfirst($facility->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>{{ $facility->email ?? 'N/A' }}</div>
                    <div>{{ $facility->phone ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $facility->capacity ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $facility->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $facility->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $facility->status === 'under_maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $facility->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('super-admin.facilities.show', $facility->id) }}" class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200">
                        <i class="fas fa-eye text-sm"></i>
                        <span class="ml-1 text-xs font-medium">View</span>
                    </a>
                    <a href="{{ route('super-admin.facilities.edit', $facility->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                        <i class="fas fa-edit text-sm"></i>
                        <span class="ml-1 text-xs font-medium">Edit</span>
                    </a>
                    <form action="{{ route('super-admin.facilities.destroy', $facility->id) }}" method="POST" class="inline-block">
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
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-hospital text-4xl mb-2"></i>
                    <p>No facilities found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $facilities->links() }}
</div>
@endsection
