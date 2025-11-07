<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use App\Models\Medication;
use App\Models\PatientMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicationController extends Controller
{
    /**
     * Display medications for assigned patient
     */
    public function index($patientId)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);

        $medications = $patient->medications()
                              ->with(['medication', 'prescriber'])
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('clinician.medications.index', compact('patient', 'medications'));
    }

    /**
     * Show form to prescribe medication
     */
    public function create($patientId)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);
        $medications = Medication::where('organization_id', $clinician->organization_id)->get();

        return view('clinician.medications.create', compact('patient', 'medications'));
    }

    /**
     * Prescribe medication to patient
     */
    public function store($patientId, Request $request)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);

        $validator = Validator::make($request->all(), [
            'medication_id' => 'required|exists:medications,id',
            'dosage' => 'required|numeric|min:0.1',
            'dosage_unit' => 'required|string|max:20',
            'frequency' => 'required|string|max:255',
            'times_per_day' => 'required|integer|min:1|max:10',
            'times' => 'required|array',
            'times.*' => 'required|date_format:H:i',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'instructions' => 'nullable|string',
            'prescriber_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $prescription = $patient->medications()->create([
            'medication_id' => $request->medication_id,
            'prescriber_id' => $clinician->id,
            'dosage' => $request->dosage,
            'dosage_unit' => $request->dosage_unit,
            'frequency' => $request->frequency,
            'times_per_day' => $request->times_per_day,
            'times' => $request->times,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'instructions' => $request->instructions,
            'prescriber_notes' => $request->prescriber_notes,
            'consent_status' => 'pending',
            'status' => 'active',
        ]);

        // Notify patient
        $patient->user->notifications()->create([
            'type' => 'medication_prescribed',
            'title' => 'New Medication Prescribed',
            'body' => 'A new medication has been prescribed to you. Please review and accept.',
            'priority' => 'high',
            'data' => ['prescription_id' => $prescription->id],
        ]);

        activity()
            ->causedBy($clinician)
            ->performedOn($prescription)
            ->log('Medication prescribed to patient');

        return redirect()->route('clinician.patients.show', $patientId)
                        ->with('success', 'Medication prescribed successfully. Awaiting patient consent.');
    }

    /**
     * Update prescription
     */
    public function update($prescriptionId, Request $request)
    {
        $clinician = auth()->user();
        $prescription = PatientMedication::findOrFail($prescriptionId);

        // Verify clinician has access to this patient
        $patient = $clinician->assignedPatients()->findOrFail($prescription->patient_id);

        $validator = Validator::make($request->all(), [
            'dosage' => 'required|numeric|min:0.1',
            'frequency' => 'required|string|max:255',
            'times_per_day' => 'required|integer|min:1|max:10',
            'times' => 'required|array',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $prescription->update($request->only([
            'dosage', 'frequency', 'times_per_day', 'times', 'instructions'
        ]));

        activity()
            ->causedBy($clinician)
            ->performedOn($prescription)
            ->log('Medication prescription updated');

        return back()->with('success', 'Prescription updated successfully.');
    }

    /**
     * Pause medication
     */
    public function pause($prescriptionId)
    {
        $clinician = auth()->user();
        $prescription = PatientMedication::findOrFail($prescriptionId);
        $prescription->update(['status' => 'paused']);

        activity()
            ->causedBy($clinician)
            ->performedOn($prescription)
            ->log('Medication paused');

        return back()->with('success', 'Medication paused.');
    }

    /**
     * Discontinue medication
     */
    public function discontinue($prescriptionId)
    {
        $clinician = auth()->user();
        $prescription = PatientMedication::findOrFail($prescriptionId);
        $prescription->update(['status' => 'discontinued']);

        activity()
            ->causedBy($clinician)
            ->performedOn($prescription)
            ->log('Medication discontinued');

        return back()->with('success', 'Medication discontinued.');
    }
}
