<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthAlert;
use Illuminate\Http\Request;

class AlertApiController extends Controller
{
    /**
     * Get all alerts for patient
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'patient') {
            $alerts = $user->patientProfile->healthAlerts()
                          ->with('acknowledgedBy')
                          ->orderBy('created_at', 'desc')
                          ->paginate(50);
        } else {
            // Clinician
            $alerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($user) {
                $q->where('clinician_id', $user->id);
            })
            ->with(['patient.user', 'acknowledgedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        }

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Get unresolved alerts only
     */
    public function unresolved(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'patient') {
            $alerts = $user->patientProfile->unresolvedAlerts;
        } else {
            $alerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($user) {
                $q->where('clinician_id', $user->id);
            })
            ->with(['patient.user'])
            ->unresolved()
            ->orderBy('created_at', 'desc')
            ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Get critical alerts only
     */
    public function critical(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'patient') {
            $alerts = $user->patientProfile->healthAlerts()
                          ->critical()
                          ->unresolved()
                          ->orderBy('created_at', 'desc')
                          ->get();
        } else {
            $alerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($user) {
                $q->where('clinician_id', $user->id);
            })
            ->with(['patient.user'])
            ->critical()
            ->unresolved()
            ->orderBy('created_at', 'desc')
            ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }

    /**
     * Acknowledge alert (clinician only)
     */
    public function acknowledge($id, Request $request)
    {
        $user = $request->user();

        if (!$user->isClinician()) {
            return response()->json([
                'success' => false,
                'message' => 'Only clinicians can acknowledge alerts'
            ], 403);
        }

        $alert = HealthAlert::findOrFail($id);

        // Verify clinician has access to this patient
        $hasAccess = $user->assignedPatients()
                         ->where('patient_profiles.id', $alert->patient_id)
                         ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $alert->acknowledge($user, $request->note);

        return response()->json([
            'success' => true,
            'message' => 'Alert acknowledged',
            'data' => $alert
        ]);
    }

    /**
     * Resolve alert (clinician only)
     */
    public function resolve($id, Request $request)
    {
        $user = $request->user();

        if (!$user->isClinician()) {
            return response()->json([
                'success' => false,
                'message' => 'Only clinicians can resolve alerts'
            ], 403);
        }

        $alert = HealthAlert::findOrFail($id);

        // Verify clinician has access to this patient
        $hasAccess = $user->assignedPatients()
                         ->where('patient_profiles.id', $alert->patient_id)
                         ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $alert->resolve($request->resolution_note);

        return response()->json([
            'success' => true,
            'message' => 'Alert resolved',
            'data' => $alert
        ]);
    }
}
