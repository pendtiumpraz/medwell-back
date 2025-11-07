@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side (Smaller) -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-2xl">
            <!-- Header -->
            <div class="mb-4">
                <a href="{{ route('admin.roles.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-2 inline-block text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Roles
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Create New Role</h1>
                <p class="text-gray-600 text-sm mt-1">Define role permissions and access levels</p>
            </div>

            <!-- Permissions Preview (Compact) -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Available Permission Groups</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @php
                        $previewIcons = [
                            'users' => 'fa-users',
                            'patients' => 'fa-user-injured',
                            'organizations' => 'fa-building',
                            'facilities' => 'fa-hospital',
                            'medications' => 'fa-pills',
                            'roles' => 'fa-shield-alt',
                            'vitals' => 'fa-heartbeat',
                            'alerts' => 'fa-exclamation-triangle',
                            'messages' => 'fa-comments',
                            'wearables' => 'fa-stopwatch',
                            'documents' => 'fa-file-medical',
                            'settings' => 'fa-cog',
                            'reports' => 'fa-chart-bar',
                            'profile' => 'fa-user',
                        ];
                    @endphp
                    @foreach($permissions as $group => $perms)
                    <div class="p-2 border border-gray-200 rounded bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <i class="fas {{ $previewIcons[strtolower($group)] ?? 'fa-shield-alt' }} text-gray-600 text-xs"></i>
                            <p class="font-semibold text-gray-800 text-xs">{{ ucfirst($group) }}</p>
                        </div>
                        <p class="text-xs text-gray-500">{{ count($perms) }} perms</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Form (Smaller) -->
    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto border-l border-gray-200">
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-800 text-white p-4 z-10 shadow-lg">
            <h2 class="text-xl font-bold">Role Information</h2>
            <p class="text-purple-100 text-xs mt-1">Complete all required fields</p>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="p-4 space-y-4">
            @csrf

            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Basic Information</h3>
                
                <!-- Role Name -->
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Role Name (slug) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="e.g., custom_manager">
                    <p class="text-xs text-gray-500 mt-0.5">Lowercase, no spaces</p>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Display Name -->
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="display_name" value="{{ old('display_name') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-purple-500 @error('display_name') border-red-500 @enderror"
                        placeholder="e.g., Custom Manager">
                    @error('display_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror"
                        placeholder="Describe this role...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level -->
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Hierarchy Level <span class="text-red-500">*</span>
                    </label>
                    <select name="level" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Level</option>
                        @for($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('level') == $i ? 'selected' : '' }}>
                                Level {{ $i }} {{ $i == 0 ? '(Highest)' : '' }} {{ $i == 10 ? '(Lowest)' : '' }}
                            </option>
                        @endfor
                    </select>
                    <p class="text-xs text-gray-500 mt-0.5">0 = Highest, 10 = Lowest</p>
                    @error('level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Permissions (Compact) -->
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Permissions</h3>
                
                <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
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
                            <button type="button" onclick="toggleGroup('{{ $group }}')" class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                                Toggle All
                            </button>
                        </div>
                        <div class="space-y-1">
                            @foreach($perms as $permission)
                            <label class="flex items-start gap-2 p-1.5 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                    data-group="{{ $group }}"
                                    {{ is_array(old('permissions')) && in_array($permission->name, old('permissions')) ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <div class="flex-1">
                                    <span class="text-xs font-medium text-gray-700">{{ $permission->display_name ?? $permission->name }}</span>
                                    @if($permission->description)
                                    <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Create Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
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
