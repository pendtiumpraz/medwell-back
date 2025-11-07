@extends('layouts.app')

@section('title', 'Organizations Management')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="organizationManagement()">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Organizations Management</h1>
            <p class="text-gray-600 mt-1">Manage all healthcare organizations in the system</p>
        </div>
        <button 
            @click="openCreate()"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-lg"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Add Organization
        </button>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-200">
            <i class="fas fa-filter text-primary"></i>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filter Organizations</h3>
        </div>
        
        <form method="GET" action="{{ route('super-admin.organizations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-gray-500"></i>
                    Search
                </label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Name, email, code..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                >
            </div>
            
            <!-- Type -->
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-building text-gray-500"></i>
                    Organization Type
                </label>
                <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="">All Types</option>
                    <option value="hospital" {{ request('type') == 'hospital' ? 'selected' : '' }}>🏥 Hospital</option>
                    <option value="clinic" {{ request('type') == 'clinic' ? 'selected' : '' }}>🏥 Clinic</option>
                    <option value="pharmacy" {{ request('type') == 'pharmacy' ? 'selected' : '' }}>💊 Pharmacy</option>
                    <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>🔬 Laboratory</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>📋 Other</option>
                </select>
            </div>
            
            <!-- Status -->
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-gray-500"></i>
                    Status
                </label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✓ Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⭕ Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>🚫 Suspended</option>
                </select>
            </div>
            
            <!-- Submit -->
            <div class="flex items-end">
                <button 
                    type="submit" 
                    class="w-full px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-2 font-medium"
                >
                    <i class="fas fa-filter"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Organizations Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($organizations as $org)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Organization Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-semibold">
                                        {{ substr($org->name, 0, 2) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $org->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $org->code }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ ucfirst($org->type) }}
                            </span>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $org->email ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $org->phone ?? 'N/A' }}</div>
                        </td>

                        <!-- Users -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center gap-4">
                                <span title="Total Users">👥 {{ $org->users_count ?? 0 }}</span>
                                <span title="Total Facilities">🏥 {{ $org->facilities_count ?? 0 }}</span>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($org->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Active
                                </span>
                            @elseif($org->status === 'inactive')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                    Inactive
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Suspended
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('super-admin.organizations.show', $org->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors" title="View Details">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                </a>
                                <button @click="openEdit({{ $org->id }})" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('super-admin.organizations.destroy', $org->id) }}" class="inline" onsubmit="return confirm('Delete this organization?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-gray-500 text-lg">No organizations found</p>
                            <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new organization</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $organizations->links() }}
        </div>
    </div>

    <!-- RIGHT SIDEBAR MODAL - INSIDE Alpine scope -->
    <div 
        x-show="rightSidebarOpen"
        @click.self="closeModal()"
        class="fixed inset-0 bg-black/50 z-40"
        x-cloak
        style="display: none;"
    >
        <aside 
            x-show="rightSidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-96 bg-white shadow-2xl overflow-y-auto"
            @click.stop
        >
<div x-show="rightSidebarOpen" class="h-full flex flex-col bg-white">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-primary to-primary-dark">
        <h2 class="text-xl font-bold text-white font-sora" x-text="editMode ? 'Edit Organization' : 'Add New Organization'"></h2>
        <button @click="closeModal()" class="text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Form Content -->
    <div class="flex-1 overflow-y-auto p-6">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">Validation Errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        <form :action="formAction" method="POST" @submit="handleSubmit">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Basic Information</h3>

                <!-- Organization Name -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Organization Name *</label>
                    <input 
                        type="text" 
                        name="name"
                        x-model="formData.name"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="e.g., General Hospital"
                    >
                </div>

                <!-- Organization Code -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Organization Code *</label>
                    <input 
                        type="text" 
                        name="code"
                        x-model="formData.code"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="e.g., ORG001"
                    >
                </div>

                <!-- Type -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                    <select name="type" x-model="formData.type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="">Select Type</option>
                        <option value="hospital">Hospital</option>
                        <option value="clinic">Clinic</option>
                        <option value="pharmacy">Pharmacy</option>
                        <option value="laboratory">Laboratory</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select name="status" x-model="formData.status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Contact Information</h3>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email"
                        x-model="formData.email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="organization@example.com"
                    >
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                    <input 
                        type="tel" 
                        name="phone"
                        x-model="formData.phone"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="+62 21 1234 5678"
                    >
                </div>

                <!-- Website -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                    <input 
                        type="url" 
                        name="website"
                        x-model="formData.website"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="https://example.com"
                    >
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Address</h3>

                <!-- Street Address -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Street Address</label>
                    <textarea 
                        name="address"
                        x-model="formData.address"
                        rows="2"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Full street address"
                    ></textarea>
                </div>

                <!-- City & State -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                        <input 
                            type="text" 
                            name="city"
                            x-model="formData.city"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="City"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">State/Province</label>
                        <input 
                            type="text" 
                            name="state"
                            x-model="formData.state"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="State"
                        >
                    </div>
                </div>

                <!-- Country & Postal Code -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                        <input 
                            type="text" 
                            name="country"
                            x-model="formData.country"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Country"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                        <input 
                            type="text" 
                            name="postal_code"
                            x-model="formData.postal_code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Postal Code"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2 border-t border-gray-200 -mx-6 px-6">
                <button 
                    type="button"
                    @click="closeModal()"
                    class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-3 rounded-lg font-semibold transition-colors shadow-lg flex items-center justify-center text-white"
                    style="background: linear-gradient(135deg, #863588 0%, #6B2A6E 100%); min-height: 48px;"
                >
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Saving...' : (editMode ? 'Update Organization' : 'Create Organization')" style="color: white; font-weight: 600;">Create Organization</span>
                </button>
            </div>
        </form>
    </div>
</div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
function organizationManagement() {
    return {
        rightSidebarOpen: false,
        editMode: false,
        editId: null,
        formAction: '',
        formMethod: 'POST',
        isSubmitting: false,
        formData: {
            name: '',
            code: '',
            type: '',
            email: '',
            phone: '',
            address: '',
            city: '',
            state: '',
            country: '',
            postal_code: '',
            website: '',
            status: 'active'
        },
        
        init() {
            @if(session('success'))
                this.showToast('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToast('{{ session('error') }}', 'error');
            @endif
            
            // Re-open modal if there are validation errors
            @if($errors->any())
                console.log('Validation errors detected, reopening modal...');
                this.openCreate();
                // Restore old input values
                this.$nextTick(() => {
                    this.formData = {
                        name: '{{ old('name') }}',
                        code: '{{ old('code') }}',
                        type: '{{ old('type') }}',
                        email: '{{ old('email') }}',
                        phone: '{{ old('phone') }}',
                        address: '{{ old('address') }}',
                        city: '{{ old('city') }}',
                        state: '{{ old('state') }}',
                        country: '{{ old('country') }}',
                        postal_code: '{{ old('postal_code') }}',
                        website: '{{ old('website') }}',
                        status: '{{ old('status', 'active') }}'
                    };
                });
            @endif
        },
        
        openCreate() {
            this.editMode = false;
            this.editId = null;
            this.formAction = '{{ route('super-admin.organizations.store') }}';
            this.formMethod = 'POST';
            this.resetFormData();
            this.rightSidebarOpen = true;
        },
        
        async openEdit(id) {
            this.editMode = true;
            this.editId = id;
            this.formAction = `/super-admin/organizations/${id}`;
            this.formMethod = 'PUT';
            this.rightSidebarOpen = true;
            
            try {
                const response = await fetch(`/super-admin/organizations/${id}/json`);
                if (!response.ok) throw new Error('Failed to fetch organization data');
                
                const data = await response.json();
                
                this.formData.name = data.name || '';
                this.formData.code = data.code || '';
                this.formData.type = data.type || '';
                this.formData.email = data.email || '';
                this.formData.phone = data.phone || '';
                this.formData.address = data.address || '';
                this.formData.city = data.city || '';
                this.formData.state = data.state || '';
                this.formData.country = data.country || '';
                this.formData.postal_code = data.postal_code || '';
                this.formData.website = data.website || '';
                this.formData.status = data.status || 'active';
                
                console.log('Organization data loaded:', data);
            } catch (error) {
                console.error('Error fetching organization:', error);
                this.showToast('Failed to load organization data', 'error');
                this.closeModal();
            }
        },
        
        resetFormData() {
            this.formData = {
                name: '',
                code: '',
                type: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                state: '',
                country: '',
                postal_code: '',
                website: '',
                status: 'active'
            };
        },
        
        closeModal() {
            this.rightSidebarOpen = false;
            setTimeout(() => {
                this.resetFormData();
                this.editMode = false;
                this.editId = null;
            }, 300);
        },
        
        handleSubmit(e) {
            console.log('=== FORM SUBMIT ===');
            console.log('Edit Mode:', this.editMode);
            console.log('Form Action:', this.formAction);
            console.log('Form Method:', this.formMethod);
            console.log('Form Data:', this.formData);
            this.isSubmitting = true;
        },
        
        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }
}
</script>
@endpush
