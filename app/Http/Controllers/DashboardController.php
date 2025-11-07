<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientProfile;
use App\Models\HealthAlert;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        return match($user->role) {
            'super_admin' => redirect()->route('super-admin.dashboard'),
            'organization_admin', 'admin' => redirect()->route('admin.dashboard'),
            'clinician', 'health_coach' => redirect()->route('clinician.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default => abort(403),
        };
    }

    public function superAdmin()
    {
        $stats = [
            'total_organizations' => \App\Models\Organization::count(),
            'total_users' => User::count(),
            'total_patients' => PatientProfile::count(),
            'critical_alerts' => HealthAlert::critical()->unresolved()->count(),
        ];

        return view('super-admin.dashboard', compact('stats'));
    }

    public function admin()
    {
        $user = auth()->user();
        
        $stats = [
            'total_patients' => PatientProfile::whereHas('user', function($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            })->count(),
            'total_clinicians' => User::where('organization_id', $user->organization_id)
                                     ->whereIn('role', ['clinician', 'health_coach'])
                                     ->count(),
            'pending_onboarding' => PatientProfile::whereHas('user', function($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            })->notOnboarded()->count(),
            'critical_alerts' => HealthAlert::whereHas('patient.user', function($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
            })->critical()->unresolved()->count(),
        ];

        $recentPatients = PatientProfile::whereHas('user', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentPatients'));
    }

    public function clinician()
    {
        $user = auth()->user();
        
        $assignedPatients = $user->assignedPatients()->count();
        $criticalAlerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($user) {
            $q->where('clinician_id', $user->id);
        })->critical()->unresolved()->count();

        $todayAlerts = HealthAlert::whereHas('patient.assignedClinicians', function($q) use ($user) {
            $q->where('clinician_id', $user->id);
        })->whereDate('created_at', today())->count();

        $patients = $user->assignedPatients()
                        ->with(['latestVitals', 'unresolvedAlerts', 'activeMedications'])
                        ->get();

        return view('clinician.dashboard', compact('assignedPatients', 'criticalAlerts', 'todayAlerts', 'patients'));
    }

    public function patient()
    {
        $user = auth()->user();
        $patient = $user->patientProfile;

        if (!$patient->onboarding_completed) {
            return redirect()->route('patient.onboarding');
        }

        $stats = [
            'wearable_connected' => $patient->hasWearableConnected(),
            'active_medications' => $patient->activeMedications()->count(),
            'unresolved_alerts' => $patient->unresolvedAlerts()->count(),
            'wellness_score' => $patient->getTodayWellnessScore(),
        ];

        $latestVitals = $patient->latestVitals()->take(5)->get();
        $todayWearable = $patient->todayWearableData()->first();
        $upcomingMedications = $patient->activeMedications()
                                      ->with(['medication', 'schedules' => function($q) {
                                          $q->whereDate('scheduled_date', today())
                                            ->where('status', 'pending');
                                      }])
                                      ->get();

        return view('patient.dashboard', compact('patient', 'stats', 'latestVitals', 'todayWearable', 'upcomingMedications'));
    }
}
