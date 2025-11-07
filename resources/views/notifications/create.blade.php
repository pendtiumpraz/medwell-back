@extends('layouts.app')

@section('title', 'Send Notification')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="text-primary hover:text-primary-dark font-medium mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i> Back to Notifications
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Send Notification</h1>
        <p class="text-gray-600 mt-1">Send notifications to users based on their roles</p>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <span class="font-semibold">Your Role:</span> {{ auth()->user()->roles->first()->display_name ?? 'N/A' }}
                </p>
                <p class="text-sm text-blue-700 mt-1">
                    You can send notifications to: 
                    <span class="font-semibold">
                        @foreach($availableRoles as $role)
                            {{ ucfirst(str_replace('_', ' ', $role)) }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
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

        <form action="{{ route('notifications.store') }}" method="POST">
            @csrf

            <!-- Recipient Type -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-users text-gray-500"></i>
                    Send To *
                </label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="recipient_type" value="user" class="w-4 h-4 text-primary" onchange="toggleRecipientFields()" required>
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-900">Specific User(s)</span>
                            <p class="text-xs text-gray-600">Select individual users to receive the notification</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="recipient_type" value="role" class="w-4 h-4 text-primary" onchange="toggleRecipientFields()">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-900">All Users of a Role</span>
                            <p class="text-xs text-gray-600">Send to all users belonging to a specific role</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="recipient_type" value="all" class="w-4 h-4 text-primary" onchange="toggleRecipientFields()">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-900">All Available Roles</span>
                            <p class="text-xs text-gray-600">Send to all users you can notify</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Specific Users Selection -->
            <div id="user-selection" class="mb-6 hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-gray-500"></i>
                    Select Users
                </label>
                <select name="recipient_ids[]" multiple class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" size="8">
                    @foreach($recipients as $user)
                    <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Cmd on Mac) to select multiple users</p>
            </div>

            <!-- Role Selection -->
            <div id="role-selection" class="mb-6 hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-gray-500"></i>
                    Select Role
                </label>
                <select name="recipient_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="">Select a role</option>
                    @foreach($availableRoles as $role)
                    <option value="{{ $role }}">{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <!-- Notification Type -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-tag text-gray-500"></i>
                    Notification Type *
                </label>
                <div class="grid grid-cols-5 gap-3">
                    <label class="flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                        <input type="radio" name="type" value="info" class="hidden peer" required>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-2 peer-checked:bg-blue-500 peer-checked:text-white">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <span class="text-sm font-medium">Info</span>
                    </label>

                    <label class="flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition-colors">
                        <input type="radio" name="type" value="success" class="hidden peer">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-2 peer-checked:bg-green-500 peer-checked:text-white">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <span class="text-sm font-medium">Success</span>
                    </label>

                    <label class="flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-yellow-50 transition-colors">
                        <input type="radio" name="type" value="warning" class="hidden peer">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-2 peer-checked:bg-yellow-500 peer-checked:text-white">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <span class="text-sm font-medium">Warning</span>
                    </label>

                    <label class="flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-red-50 transition-colors">
                        <input type="radio" name="type" value="error" class="hidden peer">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-2 peer-checked:bg-red-500 peer-checked:text-white">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                        <span class="text-sm font-medium">Error</span>
                    </label>

                    <label class="flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-purple-50 transition-colors">
                        <input type="radio" name="type" value="alert" class="hidden peer">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-2 peer-checked:bg-purple-500 peer-checked:text-white">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <span class="text-sm font-medium">Alert</span>
                    </label>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading text-gray-500"></i>
                    Title *
                </label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title') }}"
                    required
                    maxlength="255"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                    placeholder="e.g., System Maintenance Notice"
                >
            </div>

            <!-- Message -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-comment-alt text-gray-500"></i>
                    Message *
                </label>
                <textarea 
                    name="message" 
                    required
                    rows="5"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                    placeholder="Enter your message here..."
                >{{ old('message') }}</textarea>
            </div>

            <!-- Action URL (Optional) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-link text-gray-500"></i>
                    Action URL (Optional)
                </label>
                <input 
                    type="url" 
                    name="action_url" 
                    value="{{ old('action_url') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                    placeholder="https://example.com/page"
                >
                <p class="text-xs text-gray-500 mt-1">Optional link where users will be redirected when clicking the notification</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-2"
                >
                    <i class="fas fa-paper-plane"></i>
                    Send Notification
                </button>
                <a 
                    href="{{ route('notifications.index') }}" 
                    class="px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition-colors text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleRecipientFields() {
    const recipientType = document.querySelector('input[name="recipient_type"]:checked')?.value;
    const userSelection = document.getElementById('user-selection');
    const roleSelection = document.getElementById('role-selection');

    userSelection.classList.add('hidden');
    roleSelection.classList.add('hidden');

    if (recipientType === 'user') {
        userSelection.classList.remove('hidden');
    } else if (recipientType === 'role') {
        roleSelection.classList.remove('hidden');
    }
}

// Show success message
@if(session('success'))
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg z-50';
    toast.textContent = '{{ session("success") }}';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
@endif
</script>
@endpush
@endsection
