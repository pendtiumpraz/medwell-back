<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientMedication;
use App\Models\MedicationSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicationApiController extends Controller
{
    /**
     * Get patient medications
     */
    public function index(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $medications = $patient->medications()
                              ->with(['medication', 'prescriber'])
                              ->when($request->status, function($q) use ($request) {
                                  $q->where('status', $request->status);
                              })
                              ->orderBy('created_at', 'desc')
                              ->get();

        return response()->json([
            'success' => true,
            'data' => $medications
        ]);
    }

    /**
     * Get medication detail
     */
    public function show($id, Request $request)
    {
        $patient = $request->user()->patientProfile;
        
        $medication = $patient->medications()
                             ->with(['medication', 'prescriber', 'schedules'])
                             ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $medication
        ]);
    }

    /**
     * Accept/decline medication consent
     */
    public function consent($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,declined',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;
        $medication = $patient->medications()->findOrFail($id);

        $medication->update([
            'consent_status' => $request->status,
            'consent_given_at' => now(),
            'consent_comment' => $request->comment,
        ]);

        // Notify prescriber
        $medication->prescriber->notifications()->create([
            'type' => 'medication_consent',
            'title' => 'Patient Consent Response',
            'body' => "Patient has {$request->status} the medication.",
            'data' => ['prescription_id' => $medication->id],
        ]);

        return response()->json([
            'success' => true,
            'message' => "Medication {$request->status}",
            'data' => $medication
        ]);
    }

    /**
     * Get medication schedule
     */
    public function schedule(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $schedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id);
        })
        ->with('patientMedication.medication')
        ->when($request->date, function($q) use ($request) {
            $q->whereDate('scheduled_date', $request->date);
        })
        ->orderBy('scheduled_date')
        ->orderBy('scheduled_time')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Get today's medication schedule
     */
    public function todaySchedule(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $schedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id)->where('status', 'active');
        })
        ->with('patientMedication.medication')
        ->today()
        ->orderBy('scheduled_time')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Log medication adherence
     */
    public function log(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:medication_schedules,id',
            'status' => 'required|in:taken,missed,delayed',
            'taken_at' => 'nullable|date',
            'delayed_minutes' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = MedicationSchedule::findOrFail($request->schedule_id);

        // Verify this schedule belongs to the patient
        $patient = $request->user()->patientProfile;
        if ($schedule->patientMedication->patient_id !== $patient->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $schedule->update([
            'status' => $request->status,
            'taken_at' => $request->status === 'taken' ? ($request->taken_at ?? now()) : null,
            'delayed_time' => $request->delayed_minutes,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medication log updated',
            'data' => $schedule
        ]);
    }

    /**
     * Mark as taken
     */
    public function markTaken($scheduleId, Request $request)
    {
        $schedule = MedicationSchedule::findOrFail($scheduleId);
        
        $patient = $request->user()->patientProfile;
        if ($schedule->patientMedication->patient_id !== $patient->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $schedule->markAsTaken();

        return response()->json([
            'success' => true,
            'message' => 'Marked as taken',
            'data' => $schedule
        ]);
    }

    /**
     * Get adherence rate
     */
    public function adherenceRate(Request $request)
    {
        $patient = $request->user()->patientProfile;
        $period = $request->input('period', 'week'); // week, month, year

        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfWeek(),
        };

        $schedules = MedicationSchedule::whereHas('patientMedication', function($q) use ($patient) {
            $q->where('patient_id', $patient->id);
        })
        ->where('scheduled_date', '>=', $startDate)
        ->get();

        $total = $schedules->count();
        $taken = $schedules->where('status', 'taken')->count();
        $missed = $schedules->where('status', 'missed')->count();
        $delayed = $schedules->where('status', 'delayed')->count();

        $adherenceRate = $total > 0 ? round(($taken / $total) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'total_schedules' => $total,
                'taken' => $taken,
                'missed' => $missed,
                'delayed' => $delayed,
                'adherence_rate' => $adherenceRate,
            ]
        ]);
    }
}
