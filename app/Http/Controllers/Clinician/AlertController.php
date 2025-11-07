<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\HealthAlert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display alerts for assigned patients
     */
    public function index(Request $request)
    {
        $clinician = auth()->user();
        
        $query = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($clinician) {
            $q->where('clinician_id', $clinician->id);
        })->with(['patient.user']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('alert_type', $request->type);
        }

        // Filter by resolved status
        if ($request->filled('resolved')) {
            if ($request->resolved == '1') {
                $query->where('resolved', true);
            } else {
                $query->where('resolved', false);
            }
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('clinician.alerts.index', compact('alerts'));
    }

    /**
     * Critical alerts only
     */
    public function critical()
    {
        $clinician = auth()->user();
        
        $alerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($clinician) {
            $q->where('clinician_id', $clinician->id);
        })
        ->with(['patient.user'])
        ->critical()
        ->unresolved()
        ->orderBy('created_at', 'desc')
        ->get();

        return view('clinician.alerts.critical', compact('alerts'));
    }

    /**
     * Acknowledge alert
     */
    public function acknowledge($id, Request $request)
    {
        $clinician = auth()->user();
        $alert = HealthAlert::findOrFail($id);

        // Verify clinician has access to this patient
        $patient = $clinician->assignedPatients()->findOrFail($alert->patient_id);

        $alert->acknowledge($clinician, $request->note);

        activity()
            ->causedBy($clinician)
            ->performedOn($alert)
            ->log('Alert acknowledged by clinician');

        return back()->with('success', 'Alert acknowledged.');
    }

    /**
     * Resolve alert
     */
    public function resolve($id, Request $request)
    {
        $clinician = auth()->user();
        $alert = HealthAlert::findOrFail($id);

        // Verify clinician has access to this patient
        $patient = $clinician->assignedPatients()->findOrFail($alert->patient_id);

        $alert->resolve($request->resolution_note);

        activity()
            ->causedBy($clinician)
            ->performedOn($alert)
            ->log('Alert resolved by clinician');

        return back()->with('success', 'Alert resolved.');
    }
}
