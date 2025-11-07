@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="userManagement()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">User Management</h1>
            <p class="text-gray-600 mt-1">Manage system users and their roles</p>
        </div>
        <button @click="openCreate()" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Add User
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-200">
            <i class="fas fa-filter text-primary"></i>
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filter Users</h3>
        </div>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-gray-500"></i>
                    Search
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Username, email..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-gray-500"></i>
                    Role
                </label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="all">All Roles</option>
                    @php
                        $roleIcons = [
                            'super_admin' => '👑',
                            'organization_admin' => '🏢',
                            'admin' => '🛡️',
                            'clinician' => '👨‍⚕️',
                            'health_coach' => '❤️',
                            'patient' => '🤕',
                            'support' => '🎧',
                            'manager' => '📈',
                        ];
                    @endphp
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $roleIcons[$role->name] ?? '🔹' }} {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-gray-500"></i>
                    Status
                </label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary transition-all">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✓ Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⭕ Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>🚫 Suspended</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-2">
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Organization</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Last Login</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->username, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->organization->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'clinician' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $user->role === 'health_coach' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $user->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $routePrefix = auth()->user()->isSuperAdmin() ? 'super-admin' : 'admin';
                        @endphp
                        <div class="flex items-center gap-2">
                            <a href="{{ route($routePrefix . '.users.show', $user->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                View
                            </a>
                            <button @click="openEdit({{ $user->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                Edit
                            </button>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route($routePrefix . '.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $users->links() }}</div>
    </div>

    <!-- MODAL -->
    <div x-show="rightSidebarOpen" @click.self="closeModal()" class="fixed inset-0 bg-black/50 z-40" x-cloak>
        <aside x-show="rightSidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" class="absolute right-0 top-0 h-full w-96 bg-white shadow-2xl overflow-y-auto">
            <div class="h-full flex flex-col">
                <div class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-primary to-primary-dark">
                    <h2 class="text-xl font-bold text-white font-sora" x-text="editMode ? 'Edit User' : 'Add User'"></h2>
                    <button @click="closeModal()" class="text-white hover:text-gray-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <form :action="formAction" method="POST" @submit="handleSubmit">
                        @csrf
                        <input type="hidden" name="_method" :value="formMethod">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                            <input type="text" name="username" x-model="formData.username" :disabled="editMode" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" :class="{'bg-gray-100': editMode}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" x-model="formData.email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-gray-500"></i>
                                <span x-text="editMode ? 'New Password (Optional)' : 'Password *'"></span>
                            </label>
                            <input type="password" name="password" :required="!editMode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" :placeholder="editMode ? 'Leave blank to keep current password' : 'Enter password'">
                            <p class="text-xs text-gray-500 mt-1" x-show="editMode">Leave blank to keep current password</p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-gray-500"></i>
                                <span x-text="editMode ? 'Confirm New Password' : 'Confirm Password *'"></span>
                            </label>
                            <input type="password" name="password_confirmation" :required="!editMode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" :placeholder="editMode ? 'Confirm new password' : 'Confirm password'">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Role *</label>
                            <select name="role" x-model="formData.role" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="clinician">Clinician</option>
                                <option value="health_coach">Health Coach</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone" x-model="formData.phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="mb-4" x-show="editMode">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select name="status" x-model="formData.status" :required="editMode" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
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
function userManagement() {
    const routePrefix = '{{ auth()->user()->isSuperAdmin() ? "super-admin" : "admin" }}';
    
    return {
        rightSidebarOpen: false,
        editMode: false,
        editId: null,
        formAction: '',
        formMethod: 'POST',
        isSubmitting: false,
        formData: { username: '', email: '', role: '', phone: '', status: 'active' },
        
        init() {
            @if(session('success')) alert('✅ ' + '{{ session("success") }}'); @endif
        },
        
        openCreate() {
            this.editMode = false;
            this.formAction = `/${routePrefix}/users`;
            this.formMethod = 'POST';
            this.resetFormData();
            this.rightSidebarOpen = true;
        },
        
        async openEdit(id) {
            this.editMode = true;
            this.editId = id;
            this.formAction = `/${routePrefix}/users/${id}`;
            this.formMethod = 'PUT';
            this.rightSidebarOpen = true;
            
            try {
                const response = await fetch(`/${routePrefix}/users/${id}/json`);
                const data = await response.json();
                this.formData = { ...data };
            } catch (error) {
                alert('Error loading user data');
                this.closeModal();
            }
        },
        
        resetFormData() {
            this.formData = { username: '', email: '', role: '', phone: '', status: 'active' };
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
