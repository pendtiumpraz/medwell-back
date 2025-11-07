@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Main Content - Left Side -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.users.show', $user->id) }}" class="text-purple-600 hover:text-purple-800 font-medium mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i> Back to User Details
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit User: {{ $user->username }}</h1>
                <p class="text-gray-600 mt-1">Update user information and settings</p>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Current Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Current Role:</span>
                        <span class="ml-2 font-semibold">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Current Status:</span>
                        <span class="ml-2 font-semibold">{{ ucfirst($user->status) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Login:</span>
                        <span class="ml-2 font-semibold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Member Since:</span>
                        <span class="ml-2 font-semibold">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Edit Form -->
    <div class="w-full md:w-[500px] bg-white shadow-2xl overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 z-10">
            <h2 class="text-2xl font-bold">Edit User Information</h2>
            <p class="text-blue-100 text-sm mt-1">Update user details below</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Account Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Information</h3>
                
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror">
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Update (Optional) -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Update Password</h3>
                <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                
                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Min. 8 characters">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Re-enter new password">
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
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        @if(auth()->user()->role === 'super_admin')
                            <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="organization_admin" {{ old('role', $user->role) == 'organization_admin' ? 'selected' : '' }}>Organization Admin</option>
                        @endif
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="clinician" {{ old('role', $user->role) == 'clinician' ? 'selected' : '' }}>Clinician</option>
                        <option value="health_coach" {{ old('role', $user->role) == 'health_coach' ? 'selected' : '' }}>Health Coach</option>
                        <option value="support" {{ old('role', $user->role) == 'support' ? 'selected' : '' }}>Support</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="patient" {{ old('role', $user->role) == 'patient' ? 'selected' : '' }}>Patient</option>
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
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">No Organization</option>
                        @foreach(\App\Models\Organization::all() as $org)
                            <option value="{{ $org->id }}" {{ old('organization_id', $user->organization_id) == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Department -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">No Department</option>
                        @foreach(\App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-2 pt-3 sticky bottom-0 bg-white pb-3 border-t border-gray-200 -mx-4 px-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg text-sm">
                    <i class="fas fa-save mr-1"></i> Update User
                </button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg text-center text-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
