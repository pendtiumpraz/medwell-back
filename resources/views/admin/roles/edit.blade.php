@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.roles.show', $role->id) }}" class="text-purple-600 hover:text-purple-800 font-medium mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Role Details
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Role: {{ $role->display_name }}</h1>
                <p class="text-gray-600 mt-1">Update role permissions and settings</p>
            </div>

            <!-- Current Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Current Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Role Name:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $role->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Current Level:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $role->level }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Assigned Users:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $role->users()->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Current Permissions:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ is_array($role->permissions) ? count($role->permissions) : 0 }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Type:</span>
                        <span class="ml-2 font-semibold {{ $role->is_system ? 'text-yellow-600' : 'text-green-600' }}">
                            {{ $role->is_system ? 'System Role' : 'Custom Role' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="ml-2 font-semibold text-gray-900">{{ $role->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Edit Form -->
    <div class="w-full md:w-[600px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 z-10 shadow-lg">
            <h2 class="text-2xl font-bold">Edit Role</h2>
            <p class="text-blue-100 text-sm mt-1">Update role information below</p>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <!-- Role Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role Name (slug) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                        {{ $role->is_system && auth()->user()->role !== 'super_admin' ? 'readonly' : '' }}
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror {{ $role->is_system && auth()->user()->role !== 'super_admin' ? 'bg-gray-100' : '' }}"
                        placeholder="e.g., custom_manager">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Display Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 @error('display_name') border-red-500 @enderror"
                        placeholder="e.g., Custom Manager">
                    @error('display_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Describe this role...">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hierarchy Level <span class="text-red-500">*</span>
                    </label>
                    <select name="level" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500">
                        @for($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('level', $role->level) == $i ? 'selected' : '' }}>
                                Level {{ $i }} {{ $i == 0 ? '(Highest)' : '' }} {{ $i == 10 ? '(Lowest)' : '' }}
                            </option>
                        @endfor
                    </select>
                    <p class="text-xs text-gray-500 mt-1">0 = Highest authority, 10 = Lowest authority</p>
                    @error('level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-800">Permissions</h3>
                    <span class="text-xs text-gray-600 bg-gray-200 px-2 py-1 rounded">
                        {{ is_array($role->permissions) ? count($role->permissions) : 0 }} selected
                    </span>
                </div>
                
                <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
                    @php
                        $rolePermissions = is_array($role->permissions) ? $role->permissions : [];
                        $oldPermissions = old('permissions', $rolePermissions);
                    @endphp
                    
                    @php
                        $groupIcons = [
                            'users' => 'fa-users',
                            'user' => 'fa-users',
                            'patients' => 'fa-user-injured',
                            'patient' => 'fa-user-injured',
                            'organizations' => 'fa-building',
                            'organization' => 'fa-building',
                            'facilities' => 'fa-hospital',
                            'facility' => 'fa-hospital',
                            'departments' => 'fa-sitemap',
                            'department' => 'fa-sitemap',
                            'medications' => 'fa-pills',
                            'medication' => 'fa-pills',
                            'roles' => 'fa-shield-alt',
                            'role' => 'fa-shield-alt',
                            'permissions' => 'fa-key',
                            'permission' => 'fa-key',
                            'documents' => 'fa-file-medical',
                            'document' => 'fa-file-medical',
                            'vitals' => 'fa-heartbeat',
                            'vital' => 'fa-heartbeat',
                            'vital_signs' => 'fa-heartbeat',
                            'alerts' => 'fa-exclamation-triangle',
                            'alert' => 'fa-exclamation-triangle',
                            'health_alerts' => 'fa-exclamation-triangle',
                            'messages' => 'fa-comments',
                            'message' => 'fa-comments',
                            'messaging' => 'fa-comments',
                            'wearables' => 'fa-stopwatch',
                            'wearable' => 'fa-stopwatch',
                            'wearable_data' => 'fa-stopwatch',
                            'devices' => 'fa-mobile-alt',
                            'device' => 'fa-mobile-alt',
                            'settings' => 'fa-cog',
                            'setting' => 'fa-cog',
                            'system' => 'fa-cog',
                            'audit' => 'fa-history',
                            'audit_logs' => 'fa-history',
                            'logs' => 'fa-history',
                            'reports' => 'fa-chart-bar',
                            'report' => 'fa-chart-bar',
                            'analytics' => 'fa-chart-line',
                            'appointments' => 'fa-calendar-check',
                            'appointment' => 'fa-calendar-check',
                            'schedules' => 'fa-calendar-alt',
                            'schedule' => 'fa-calendar-alt',
                            'notifications' => 'fa-bell',
                            'notification' => 'fa-bell',
                            'geofence' => 'fa-map-marked-alt',
                            'geofencing' => 'fa-map-marked-alt',
                            'geolocation' => 'fa-location-arrow',
                        ];
                    @endphp
                    @foreach($permissions as $group => $perms)
                    <div class="border border-gray-200 rounded-lg p-2 bg-white">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $groupIcons[strtolower($group)] ?? 'fa-shield-alt' }} text-gray-600"></i>
                                <h4 class="font-semibold text-gray-800 text-sm">{{ ucfirst($group) }}</h4>
                            </div>
                            <button type="button" onclick="toggleGroup('{{ $group }}')" class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 bg-blue-50 rounded">
                                Toggle All
                            </button>
                        </div>
                        <div class="space-y-1">
                            @foreach($perms as $permission)
                            <label class="flex items-start gap-2 p-1.5 hover:bg-gray-50 rounded cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                    data-group="{{ $group }}"
                                    {{ in_array($permission->name, $oldPermissions) ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <span class="text-xs font-medium text-gray-700 block">{{ $permission->display_name ?? $permission->name }}</span>
                                    @if($permission->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($role->is_system)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div>
                        <p class="font-semibold text-yellow-800">System Role</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            This is a system role. Some fields cannot be modified by non-super admins.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Update Role
                </button>
                <a href="{{ route('admin.roles.show', $role->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleGroup(group) {
    const checkboxes = document.querySelectorAll(`input[data-group="${group}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection
