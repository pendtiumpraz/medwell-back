<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use App\Models\VitalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VitalSignController extends Controller
{
    /**
     * Display vital signs for assigned patient
     */
    public function index($patientId, Request $request)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);

        $query = $patient->vitalSigns();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Date range
        if ($request->filled('from')) {
            $query->where('recorded_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('recorded_at', '<=', $request->to);
        }

        $vitals = $query->orderBy('recorded_at', 'desc')->paginate(50);

        return view('clinician.vitals.index', compact('patient', 'vitals'));
    }

    /**
     * Store vital signs for patient
     */
    public function store($patientId, Request $request)
    {
        $clinician = auth()->user();
        $patient = $clinician->assignedPatients()->findOrFail($patientId);

        $validator = Validator::make($request->all(), [
            'recorded_at' => 'required|date',
            'systolic' => 'nullable|integer|min:50|max:300',
            'diastolic' => 'nullable|integer|min:30|max:200',
            'pulse' => 'nullable|integer|min:30|max:250',
            'glucose_value' => 'nullable|numeric|min:20|max:600',
            'glucose_context' => 'nullable|in:fasting_8hr,before_meal,after_meal_2hr,bedtime,random',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'spo2_value' => 'nullable|integer|min:50|max:100',
            'weight' => 'nullable|numeric|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vital = $patient->vitalSigns()->create(array_merge(
            $request->only([
                'recorded_at', 'systolic', 'diastolic', 'pulse',
                'glucose_value', 'glucose_context', 'temperature',
                'spo2_value', 'weight'
            ]),
            [
                'source' => 'clinician',
                'device_type' => 'manual_entry',
            ]
        ));

        activity()
            ->causedBy($clinician)
            ->performedOn($vital)
            ->log('Vital signs recorded by clinician');

        return back()->with('success', 'Vital signs recorded successfully.');
    }
}
