<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show profile
     */
    public function show()
    {
        $patient = auth()->user()->patientProfile;
        
        // Load additional data
        $latestVital = \App\Models\VitalSign::where('patient_id', $patient->id)
            ->latest()
            ->first();
            
        $pendingConsentMeds = \App\Models\PatientMedication::where('patient_id', $patient->id)
            ->where('consent_status', 'pending')
            ->with('medication')
            ->get();
            
        return view('patient.profile.index', compact('patient', 'latestVital', 'pendingConsentMeds'));
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $patient = auth()->user()->patientProfile;
        return view('patient.profile.edit', compact('patient'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patientProfile;

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'height' => 'nullable|numeric|min:50|max:300',
            'weight' => 'nullable|numeric|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user
        $user->update([
            'phone' => $request->phone,
        ]);

        // Update patient profile
        $patient->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'height' => $request->height,
            'weight' => $request->weight,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = auth()->user();

        // Delete old avatar
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar uploaded successfully.');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
