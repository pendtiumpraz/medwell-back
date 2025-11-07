<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\PatientMedication;
use App\Models\MedicationSchedule;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display patient's medications
     */
    public function index()
    {
        $patient = auth()->user()->patientProfile;

        $medications = $patient->medications()
                              ->with(['medication', 'prescriber'])
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        $pendingConsentMeds = $patient->medications()
                                     ->where('consent_status', 'pending')
                                     ->with('medication')
                                     ->get();

        return view('patient.medications.index', compact('medications', 'pendingConsentMeds'));
    }

    /**
     * Accept/decline medication consent
     */
    public function consent($id, Request $request)
    {
        $patient = auth()->user()->patientProfile;
        $medication = $patient->medications()->findOrFail($id);

        $medication->update([
            'consent_status' => $request->status, // 'accepted' or 'declined'
            'consent_given_at' => now(),
            'consent_comment' => $request->comment,
        ]);

        // Notify prescriber
        $medication->prescriber->notifications()->create([
            'type' => 'medication_consent',
            'title' => 'Patient Consent Response',
            'body' => "Patient has {$request->status} the medication: {$medication->medication->name}",
            'data' => ['prescription_id' => $medication->id],
        ]);

        return back()->with('success', "Medication {$request->status} successfully.");
    }

    /**
     * View medication schedule
     */
    public function schedule()
    {
        $patient = auth()->user()->patientProfile;

        $todaySchedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id)->where('status', 'active');
        })
        ->with('patientMedication.medication')
        ->today()
        ->orderBy('scheduled_time')
        ->get();

        $upcomingSchedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id)->where('status', 'active');
        })
        ->with('patientMedication.medication')
        ->where('scheduled_date', '>', today())
        ->where('scheduled_date', '<=', today()->addDays(7))
        ->orderBy('scheduled_date')
        ->orderBy('scheduled_time')
        ->get();

        return view('patient.medications.schedule', compact('todaySchedules', 'upcomingSchedules'));
    }

    /**
     * Log medication taken
     */
    public function log(Request $request)
    {
        $patient = auth()->user()->patientProfile;
        $schedule = MedicationSchedule::findOrFail($request->schedule_id);

        // Verify this schedule belongs to this patient
        if ($schedule->patientMedication->patient_id !== $patient->id) {
            abort(403);
        }

        $schedule->update([
            'status' => $request->status, // 'taken', 'missed', 'delayed'
            'taken_at' => $request->status === 'taken' ? now() : null,
            'delayed_time' => $request->delayed_minutes,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Medication log updated.');
    }

    /**
     * Get adherence statistics
     */
    public function adherence()
    {
        $patient = auth()->user()->patientProfile;

        $schedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id);
        })
        ->where('scheduled_date', '>=', now()->subDays(30))
        ->get();

        $total = $schedules->count();
        $taken = $schedules->where('status', 'taken')->count();
        $missed = $schedules->where('status', 'missed')->count();
        $delayed = $schedules->where('status', 'delayed')->count();

        $adherenceRate = $total > 0 ? round(($taken / $total) * 100, 2) : 0;

        return view('patient.medications.adherence', compact('adherenceRate', 'total', 'taken', 'missed', 'delayed'));
    }
}
