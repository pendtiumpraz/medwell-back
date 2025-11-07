<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display user's notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get unread notifications for dropdown
     */
    public function unread()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Show create notification form (for sending to lower levels)
     */
    public function create()
    {
        $user = auth()->user();
        $currentRole = $user->roles->first();
        
        // Get roles that current user can send notifications to
        $availableRoles = $this->getAvailableRoles($currentRole);
        
        // Get users of those roles
        $recipients = User::whereHas('roles', function($query) use ($availableRoles) {
            $query->whereIn('name', $availableRoles);
        })->select('id', 'username', 'email')->get();

        return view('notifications.create', compact('recipients', 'availableRoles'));
    }

    /**
     * Store new notification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:user,role,all',
            'recipient_ids' => 'required_if:recipient_type,user|array',
            'recipient_ids.*' => 'exists:users,id',
            'recipient_role' => 'required_if:recipient_type,role|string',
            'type' => 'required|in:info,success,warning,error,alert',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action_url' => 'nullable|url',
        ]);

        $user = auth()->user();
        $currentRole = $user->roles->first();
        $availableRoles = $this->getAvailableRoles($currentRole);

        // Get recipient user IDs based on type
        $recipientIds = [];
        
        if ($request->recipient_type === 'user') {
            // Specific users
            $recipientIds = $request->recipient_ids;
        } elseif ($request->recipient_type === 'role') {
            // All users of a specific role
            $recipientIds = User::whereHas('roles', function($query) use ($request) {
                $query->where('name', $request->recipient_role);
            })->pluck('id')->toArray();
        } elseif ($request->recipient_type === 'all') {
            // All users in available roles
            $recipientIds = User::whereHas('roles', function($query) use ($availableRoles) {
                $query->whereIn('name', $availableRoles);
            })->pluck('id')->toArray();
        }

        // Create notifications for all recipients
        $count = 0;
        foreach ($recipientIds as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'action_url' => $validated['action_url'] ?? null,
                'data' => [
                    'sender_id' => $user->id,
                    'sender_name' => $user->username,
                ],
            ]);
            $count++;
        }

        activity()
            ->causedBy($user)
            ->log("Sent notification to {$count} users");

        return redirect()->route('notifications.create')
            ->with('success', "Notification sent to {$count} users successfully!");
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    /**
     * Get roles that current user can send notifications to
     */
    private function getAvailableRoles($currentRole)
    {
        if (!$currentRole) {
            return [];
        }

        // Define role hierarchy
        $roleHierarchy = [
            // Super Admin can send to ALL roles
            'super_admin' => ['organization_admin', 'admin', 'clinician', 'health_coach', 'manager', 'patient'],
            
            // Level 1: Can send to Level 2 only
            'organization_admin' => ['clinician', 'health_coach', 'manager'],
            'admin' => ['clinician', 'health_coach', 'manager'],
            
            // Level 2: Can send to Level 3 only
            'clinician' => ['patient'],
            'health_coach' => ['patient'],
            'manager' => ['clinician', 'health_coach'], // Manager can also send to other level 2
        ];

        return $roleHierarchy[$currentRole->name] ?? [];
    }
}
