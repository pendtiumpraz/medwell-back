@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $user->username }}</h1>
                <p class="text-gray-600 mt-1">{{ $user->email }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                @if($user->id !== auth()->id())
                    @if(!$user->trashed())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-undo mr-2"></i> Restore
                        </button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">User Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Username</p>
                        <p class="font-semibold text-gray-800">{{ $user->username }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                        <p class="font-semibold text-gray-800">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Role</p>
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                            {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $user->role === 'admin' || $user->role === 'organization_admin' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'clinician' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $user->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $user->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Organization</p>
                        <p class="font-semibold text-gray-800">{{ $user->organization->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Department</p>
                        <p class="font-semibold text-gray-800">{{ $user->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email Verified</p>
                        <p class="font-semibold text-gray-800">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i') : 'Not Verified' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Last Login</p>
                        <p class="font-semibold text-gray-800">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Last Login IP</p>
                        <p class="font-semibold text-gray-800">{{ $user->last_login_ip ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Created At</p>
                        <p class="font-semibold text-gray-800">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Updated At</p>
                        <p class="font-semibold text-gray-800">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h2>
                <div class="space-y-3">
                    @php
                        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', 'App\Models\User')
                            ->where('subject_id', $user->id)
                            ->orWhere('causer_id', $user->id)
                            ->latest()
                            ->take(10)
                            ->get();
                    @endphp
                    
                    @forelse($activities as $activity)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-history text-purple-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                                @if($activity->causer)
                                    by {{ $activity->causer->username }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">No activity recorded</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Change Password -->
        <div class="space-y-6">
            <!-- Change Password Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-key text-purple-600 mr-2"></i> Change Password
                </h2>
                <form action="{{ route('admin.users.change-password', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 @error('password') border-red-500 @enderror"
                                placeholder="Min. 8 characters">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                                placeholder="Re-enter password">
                        </div>
                        
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg">
                            <i class="fas fa-save mr-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl shadow-lg p-6 text-white">
                <h2 class="text-xl font-bold mb-4">Quick Stats</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">Account Age</span>
                        <span class="font-bold">{{ $user->created_at->diffForHumans(null, true) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">Total Logins</span>
                        <span class="font-bold">{{ $user->last_login_at ? 'Active' : 'None' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-purple-100">User ID</span>
                        <span class="font-bold">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif
@endsection
