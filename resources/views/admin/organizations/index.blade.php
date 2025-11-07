@extends('layouts.app')

@section('title', 'Organizations Management')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-sora">Organizations Management</h1>
        <p class="text-gray-600 mt-1">Manage healthcare organizations and facilities</p>
    </div>
    <a href="{{ route('super-admin.organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-lg">
        <i class="fas fa-plus mr-2"></i>
        Add Organization
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search organizations..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="">All Types</option>
                <option value="hospital" {{ request('type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                <option value="clinic" {{ request('type') == 'clinic' ? 'selected' : '' }}>Clinic</option>
                <option value="pharmacy" {{ request('type') == 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
            Apply Filters
        </button>
    </form>
</div>

<!-- Organizations Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patients</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($organizations as $org)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $org->name }}</div>
                        <div class="text-sm text-gray-500">{{ $org->code }}</div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $org->type === 'hospital' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $org->type === 'clinic' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $org->type === 'pharmacy' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $org->type === 'laboratory' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $org->type === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($org->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div>{{ $org->email }}</div>
                    <div>{{ $org->phone }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $org->users_count }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $org->patients_count }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $org->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $org->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $org->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($org->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('super-admin.organizations.show', $org->id) }}" 
                           class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="ml-1 text-xs font-medium">View</span>
                        </a>
                        
                        <a href="{{ route('super-admin.organizations.edit', $org->id) }}" 
                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="ml-1 text-xs font-medium">Edit</span>
                        </a>
                        
                        @if($org->users_count == 0)
                        <form action="{{ route('super-admin.organizations.destroy', $org->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this organization?')">
                                <i class="fas fa-trash text-sm"></i>
                                <span class="ml-1 text-xs font-medium">Delete</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-building text-6xl mb-4 opacity-20"></i>
                    <p class="text-lg">No organizations found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($organizations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $organizations->links() }}
    </div>
    @endif
</div>
@endsection
