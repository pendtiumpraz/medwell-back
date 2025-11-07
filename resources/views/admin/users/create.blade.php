@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Create New User</h1>
                <p class="text-gray-600 mt-1">Fill in the form on the right to create a new system user</p>
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="text-blue-600 text-3xl mb-2"><i class="fas fa-user-circle"></i></div>
                    <h3 class="font-semibold text-gray-800 mb-1">User Account</h3>
                    <p class="text-sm text-gray-600">Create username and email for login</p>
                </div>
                
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="text-green-600 text-3xl mb-2"><i class="fas fa-shield-alt"></i></div>
                    <h3 class="font-semibold text-gray-800 mb-1">Security</h3>
                    <p class="text-sm text-gray-600">Set strong password for security</p>
                </div>
                
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="text-purple-600 text-3xl mb-2"><i class="fas fa-user-tag"></i></div>
                    <h3 class="font-semibold text-gray-800 mb-1">Role & Access</h3>
                    <p class="text-sm text-gray-600">Assign role and organization</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Form -->
    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-800 text-white p-6 z-10">
            <h2 class="text-2xl font-bold">User Information</h2>
            <p class="text-purple-100 text-sm mt-1">Complete all required fields</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Account Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
                
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('username') border-red-500 @enderror"
                        placeholder="Enter username">
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                        placeholder="user@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('phone') border-red-500 @enderror"
                        placeholder="+62 812 3456 7890">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Security -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Security</h3>
                
                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                        placeholder="Min. 8 characters">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                        placeholder="Re-enter password">
                </div>
            </div>

            <!-- Role & Organization -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Role & Organization</h3>
                
                <!-- Role -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        @if(auth()->user()->role === 'super_admin')
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="organization_admin" {{ old('role') == 'organization_admin' ? 'selected' : '' }}>Organization Admin</option>
                        @endif
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="clinician" {{ old('role') == 'clinician' ? 'selected' : '' }}>Clinician</option>
                        <option value="health_coach" {{ old('role') == 'health_coach' ? 'selected' : '' }}>Health Coach</option>
                        <option value="support" {{ old('role') == 'support' ? 'selected' : '' }}>Support</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Organization (Super Admin only) -->
                @if(auth()->user()->role === 'super_admin')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Organization</label>
                    <select name="organization_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('organization_id') border-red-500 @enderror">
                        <option value="">No Organization</option>
                        @foreach(\App\Models\Organization::all() as $org)
                            <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Department -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('department_id') border-red-500 @enderror">
                        <option value="">No Department</option>
                        @foreach(\App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} - {{ $dept->facility->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all text-sm">
                    <i class="fas fa-save mr-1"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center transition-all text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
