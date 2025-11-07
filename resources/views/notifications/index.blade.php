@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
            <p class="text-gray-600 mt-1">Stay updated with your latest notifications</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Mark All as Read Button -->
            @if($unreadCount > 0)
            <button onclick="markAllAsRead()" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md">
                <i class="fas fa-check-double"></i>
                Mark All as Read ({{ $unreadCount }})
            </button>
            @endif

            <!-- Send Notification Button (only for non-patients) -->
            @if(!auth()->user()->hasRole('patient'))
            <a href="{{ route('notifications.create') }}" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all shadow-md">
                <i class="fas fa-paper-plane"></i>
                Send Notification
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Notifications</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $notifications->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Unread</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $unreadCount }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Read</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $notifications->total() - $unreadCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors {{ $notification->isUnread() ? 'bg-blue-50' : '' }}">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full {{ $notification->color_class }} flex items-center justify-center">
                            <i class="fas {{ $notification->icon }} text-xl"></i>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 {{ $notification->isUnread() ? 'font-bold' : '' }}">
                                    {{ $notification->title }}
                                    @if($notification->isUnread())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        New
                                    </span>
                                    @endif
                                </h3>
                                <p class="text-gray-600 mt-1">{{ $notification->message }}</p>

                                <!-- Sender Info -->
                                @if(isset($notification->data['sender_name']))
                                <p class="text-sm text-gray-500 mt-2">
                                    <i class="fas fa-user text-gray-400"></i>
                                    From: <span class="font-medium">{{ $notification->data['sender_name'] }}</span>
                                </p>
                                @endif

                                <!-- Timestamp -->
                                <p class="text-sm text-gray-400 mt-2">
                                    <i class="fas fa-clock"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" onclick="markAsRead({{ $notification->id }})" class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary-dark transition-colors">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    View
                                </a>
                                @endif

                                @if($notification->isUnread())
                                <button onclick="markAsRead({{ $notification->id }})" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 text-sm rounded-lg hover:bg-green-200 transition-colors">
                                    <i class="fas fa-check mr-1"></i>
                                    Mark Read
                                </button>
                                @endif

                                <button onclick="deleteNotification({{ $notification->id }})" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 text-sm rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Notifications</h3>
            <p class="text-gray-600">You're all caught up! No notifications to display.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    if (!confirm('Mark all notifications as read?')) return;

    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if (!confirm('Delete this notification?')) return;

    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Show success message if any
@if(session('success'))
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg z-50';
    toast.textContent = '{{ session("success") }}';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
@endif
</script>
@endpush
@endsection
