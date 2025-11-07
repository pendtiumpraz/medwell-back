<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageApiController extends Controller
{
    /**
     * Get all messages for user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $messages = Message::where(function($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('recipient_id', $user->id);
        })
        ->with(['sender', 'recipient'])
        ->orderBy('created_at', 'desc')
        ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Get message detail
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        $message = Message::where(function($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('recipient_id', $user->id);
        })
        ->with(['sender', 'recipient'])
        ->findOrFail($id);

        // Mark as read if recipient
        if ($message->recipient_id === $user->id && !$message->isRead()) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Send message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verify patient can only message assigned clinicians
        if ($user->role === 'patient') {
            $patient = $user->patientProfile;
            $canMessage = $patient->assignedClinicians()
                                 ->where('clinician_id', $request->recipient_id)
                                 ->exists();

            if (!$canMessage) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only message your assigned clinician'
                ], 403);
            }
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'message_type' => 'private',
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        // Notify recipient
        $recipient = \App\Models\User::find($request->recipient_id);
        $recipient->notifications()->create([
            'type' => 'new_message',
            'title' => 'New Message',
            'body' => substr($request->body, 0, 100),
            'action_url' => '/messages/' . $message->id,
            'priority' => 'normal',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent',
            'data' => $message
        ], 201);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($id, Request $request)
    {
        $user = $request->user();

        $message = Message::where('recipient_id', $user->id)
                         ->findOrFail($id);

        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
            'data' => $message
        ]);
    }

    /**
     * Get conversation with specific user
     */
    public function conversation($userId, Request $request)
    {
        $user = $request->user();

        $messages = Message::between($user->id, $userId)
                          ->with(['sender', 'recipient'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark unread messages as read
        Message::where('recipient_id', $user->id)
               ->where('sender_id', $userId)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }
}
