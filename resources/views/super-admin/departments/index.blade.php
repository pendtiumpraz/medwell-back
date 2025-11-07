@extends('layouts.app')

@section('title', 'Departments Management')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="departmentManagement()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Departments Management</h1>
            <p class="text-gray-600 mt-1">Manage all departments across facilities</p>
        </div>
        <button @click="openCreate()" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Add Department
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-200">
            <i class="fas fa-filter text-primary"></i>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filter Departments</h3>
        </div>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-gray-500"></i>
                    Search
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or code..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-hospital text-gray-500"></i>
                    Facility
                </label>
                <select name="facility_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>🏥 {{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-gray-500"></i>
                    Status
                </label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✓ Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⭕ Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-2 font-medium">
                    <i class="fas fa-filter"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Facility</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Head</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($departments as $dept)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $dept->name }}</div>
                        <div class="text-sm text-gray-500">Code: {{ $dept->code }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $dept->facility->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $dept->head_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $dept->phone ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($dept->status === 'active')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('super-admin.departments.show', $dept->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors" title="View Details">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </a>
                            <button @click="openEdit({{ $dept->id }})" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('super-admin.departments.destroy', $dept->id) }}" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No departments found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $departments->links() }}</div>
    </div>

    <!-- MODAL -->
    <div x-show="rightSidebarOpen" @click.self="closeModal()" class="fixed inset-0 bg-black/50 z-40" x-cloak>
        <aside x-show="rightSidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="absolute right-0 top-0 h-full w-96 bg-white shadow-2xl overflow-y-auto">
            <div class="h-full flex flex-col">
                <div class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-primary to-primary-dark">
                    <h2 class="text-xl font-bold text-white font-sora" x-text="editMode ? 'Edit Department' : 'Add Department'"></h2>
                    <button @click="closeModal()" class="text-white hover:text-gray-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <form :action="formAction" method="POST" @submit="handleSubmit">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Facility *</label>
                            <select name="facility_id" x-model="formData.facility_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Select Facility</option>
                                @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->name }} ({{ $facility->organization->name ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Department Name *</label>
                            <input type="text" name="name" x-model="formData.name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Code *</label>
                            <input type="text" name="code" x-model="formData.code" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" placeholder="e.g. CARD">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Department Head</label>
                            <input type="text" name="head_name" x-model="formData.head_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" x-model="formData.phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" x-model="formData.email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" x-model="formData.description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select name="status" x-model="formData.status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2 border-t -mx-6 px-6">
                            <button type="button" @click="closeModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-3 rounded-lg font-semibold shadow-lg flex items-center justify-center text-white" style="background: linear-gradient(135deg, #863588 0%, #6B2A6E 100%); min-height: 48px;">
                                <span x-text="isSubmitting ? 'Saving...' : (editMode ? 'Update' : 'Create')" style="color: white; font-weight: 600;">Create</span>
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
function departmentManagement() {
    return {
        rightSidebarOpen: false,
        editMode: false,
        editId: null,
        formAction: '',
        formMethod: 'POST',
        isSubmitting: false,
        formData: { facility_id: '', name: '', code: '', head_name: '', phone: '', email: '', description: '', status: 'active' },
        
        init() {
            @if(session('success')) alert('✅ ' + '{{ session("success") }}'); @endif
        },
        
        openCreate() {
            this.editMode = false;
            this.formAction = '{{ route("super-admin.departments.store") }}';
            this.formMethod = 'POST';
            this.resetFormData();
            this.rightSidebarOpen = true;
        },
        
        async openEdit(id) {
            this.editMode = true;
            this.editId = id;
            this.formAction = '/super-admin/departments/' + id;
            this.formMethod = 'PUT';
            this.rightSidebarOpen = true;
            
            try {
                const response = await fetch(`/super-admin/departments/${id}/json`);
                const data = await response.json();
                this.formData = { ...data };
            } catch (error) {
                alert('Error loading department data');
                this.closeModal();
            }
        },
        
        resetFormData() {
            this.formData = { facility_id: '', name: '', code: '', head_name: '', phone: '', email: '', description: '', status: 'active' };
        },
        
        closeModal() {
            this.rightSidebarOpen = false;
            setTimeout(() => { this.resetFormData(); this.editMode = false; }, 300);
        },
        
        handleSubmit() { this.isSubmitting = true; }
    }
}
</script>
@endpush
