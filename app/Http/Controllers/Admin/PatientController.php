<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of patients (with soft deleted)
     */
    public function index(Request $request)
    {
        $query = PatientProfile::with(['user', 'assignedClinicians']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('email', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by onboarding status
        if ($request->filled('onboarding')) {
            if ($request->onboarding === 'completed') {
                $query->onboarded();
            } else {
                $query->notOnboarded();
            }
        }

        // Filter by wearable
        if ($request->filled('wearable') && $request->wearable !== 'all') {
            $query->where('wearable_type', $request->wearable);
        }

        // Include soft deleted (Admin only)
        if ($request->filled('show_deleted') && $request->show_deleted == '1') {
            $query->withTrashed();
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show form for creating new patient
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created patient
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'racial_origin' => 'nullable|string|max:50',
            'height' => 'nullable|numeric|min:50|max:300',
            'weight' => 'nullable|numeric|min:10|max:500',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create user
        $user = User::create([
            'organization_id' => auth()->user()->organization_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'active',
            'phone' => $request->phone,
        ]);

        // Create patient profile
        $patient = PatientProfile::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'racial_origin' => $request->racial_origin,
            'height' => $request->height,
            'weight' => $request->weight,
            'blood_type' => $request->blood_type,
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($patient)
            ->log('Patient created by admin');

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified patient
     */
    public function show($id)
    {
        $patient = PatientProfile::with([
            'user',
            'medicalConditions',
            'allergies',
            'assignedClinicians',
            'latestVitals' => function($q) { $q->take(10); },
            'activeMedications',
            'unresolvedAlerts',
            'todayWearableData'
        ])->findOrFail($id);

        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Get patient data as JSON (for AJAX modal edit)
     */
    public function getJson($id)
    {
        $patient = PatientProfile::with('user')->findOrFail($id);
        
        return response()->json([
            'id' => $patient->id,
            'user_id' => $patient->user_id,
            'username' => $patient->user->username,
            'email' => $patient->user->email,
            'full_name' => $patient->full_name,
            'date_of_birth' => $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') : null,
            'gender' => $patient->gender,
            'phone' => $patient->phone,
            'address' => $patient->address,
            'height' => $patient->height,
            'weight' => $patient->weight,
            'blood_type' => $patient->blood_type,
            'racial_origin' => $patient->racial_origin,
            'onboarding_completed' => $patient->onboarding_completed,
            'wearable_type' => $patient->wearable_type,
        ]);
    }

    /**
     * Show form for editing patient
     */
    public function edit($id)
    {
        $patient = PatientProfile::with('user')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient
     */
    public function update(Request $request, $id)
    {
        $patient = PatientProfile::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'address' => 'nullable|string',
            'height' => 'nullable|numeric|min:50|max:300',
            'weight' => 'nullable|numeric|min:10|max:500',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'racial_origin' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update user
        $patient->user->update([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update patient profile
        $patient->update([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'height' => $request->height,
            'weight' => $request->weight,
            'blood_type' => $request->blood_type,
            'racial_origin' => $request->racial_origin,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($patient)
            ->log('Patient updated by admin');

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Soft delete the specified patient
     */
    public function destroy($id)
    {
        $patient = PatientProfile::findOrFail($id);
        
        // Soft delete patient profile
        $patient->delete();
        
        // Soft delete user
        $patient->user->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($patient)
            ->log('Patient soft deleted by admin');

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }

    /**
     * Restore soft deleted patient
     */
    public function restore($id)
    {
        $patient = PatientProfile::withTrashed()->findOrFail($id);
        
        // Restore patient profile
        $patient->restore();
        
        // Restore user
        $patient->user()->withTrashed()->restore();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($patient)
            ->log('Patient restored by admin');

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Patient restored successfully.');
    }

    /**
     * Permanently delete patient
     */
    public function forceDelete($id)
    {
        $patient = PatientProfile::withTrashed()->findOrFail($id);
        
        activity()
            ->causedBy(auth()->user())
            ->performedOn($patient)
            ->log('Patient permanently deleted by admin');

        // Permanently delete user (cascade will delete profile)
        $patient->user()->withTrashed()->forceDelete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient permanently deleted.');
    }

    /**
     * Assign clinician to patient
     */
    public function assignClinician(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'clinician_id' => 'required|exists:users,id',
            'role' => 'required|in:primary,secondary',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $patient = PatientProfile::findOrFail($id);

        // Attach clinician
        $patient->assignedClinicians()->attach($request->clinician_id, [
            'role' => $request->role,
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Clinician assigned successfully.');
    }

    /**
     * Remove clinician from patient
     */
    public function removeClinician(Request $request, $id)
    {
        $patient = PatientProfile::findOrFail($id);
        $patient->assignedClinicians()->detach($request->clinician_id);

        return back()->with('success', 'Clinician removed successfully.');
    }
}
