<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display assigned patients only
     */
    public function index(Request $request)
    {
        $clinician = auth()->user();
        
        $query = $clinician->assignedPatients()->with(['user', 'unresolvedAlerts']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $patients = $query->paginate(20);

        return view('clinician.patients.index', compact('patients'));
    }

    /**
     * Display patient detail
     */
    public function show($id)
    {
        $clinician = auth()->user();
        
        // Verify this patient is assigned to this clinician
        $patient = $clinician->assignedPatients()
                             ->with([
                                 'user',
                                 'medicalConditions',
                                 'allergies',
                                 'latestVitals' => function($q) { $q->take(20); },
                                 'activeMedications.medication',
                                 'unresolvedAlerts',
                                 'todayWearableData',
                             ])
                             ->findOrFail($id);

        return view('clinician.patients.show', compact('patient'));
    }
}
