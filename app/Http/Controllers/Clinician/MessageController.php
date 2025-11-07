<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display messages
     */
    public function index(Request $request)
    {
        $clinician = auth()->user();
        
        $messages = Message::where(function($q) use ($clinician) {
            $q->where('sender_id', $clinician->id)
              ->orWhere('recipient_id', $clinician->id);
        })
        ->with(['sender', 'recipient'])
        ->orderBy('created_at', 'desc')
        ->paginate(30);

        return view('clinician.messages.index', compact('messages'));
    }

    /**
     * Conversation with specific patient
     */
    public function conversation($patientId)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);
        
        $messages = Message::between($clinician->id, $patient->user_id)
                          ->with(['sender', 'recipient'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark unread messages as read
        Message::where('recipient_id', $clinician->id)
               ->where('sender_id', $patient->user_id)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        return view('clinician.messages.conversation', compact('patient', 'messages'));
    }

    /**
     * Send message to patient
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $clinician = auth()->user();

        // Verify recipient is an assigned patient
        $patient = $clinician->assignedPatients()
                            ->whereHas('user', function($q) use ($request) {
                                $q->where('id', $request->recipient_id);
                            })
                            ->first();

        if (!$patient) {
            return back()->with('error', 'You can only message assigned patients.');
        }

        $message = Message::create([
            'sender_id' => $clinician->id,
            'recipient_id' => $request->recipient_id,
            'message_type' => 'private',
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        // Notify patient
        $patient->user->notifications()->create([
            'type' => 'new_message',
            'title' => 'New Message from Clinician',
            'body' => substr($request->body, 0, 100),
            'action_url' => '/patient/messages',
            'priority' => 'normal',
        ]);

        return back()->with('success', 'Message sent.');
    }
}
