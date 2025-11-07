<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\VitalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VitalSignController extends Controller
{
    /**
     * Display vital signs history
     */
    public function index(Request $request)
    {
        $patient = auth()->user()->patientProfile;

        $query = $patient->vitalSigns();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        $vitals = $query->orderBy('recorded_at', 'desc')->paginate(50);
        
        $latestVital = $patient->vitalSigns()->latest()->first();

        return view('patient.vitals.index', compact('vitals', 'latestVital'));
    }

    /**
     * Show form to log vital signs
     */
    public function create()
    {
        return view('patient.vitals.create');
    }

    /**
     * Store blood pressure
     */
    public function storeBloodPressure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'systolic' => 'required|integer|min:50|max:300',
            'diastolic' => 'required|integer|min:30|max:200',
            'pulse' => 'required|integer|min:30|max:250',
            'recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $patient = auth()->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'pulse' => $request->pulse,
            'recorded_at' => $request->recorded_at,
            'source' => 'manual',
            'device_type' => 'patient_entry',
        ]);

        // Check for alerts
        $this->checkBloodPressureAlert($patient, $vital);

        return back()->with('success', 'Blood pressure recorded successfully.');
    }

    /**
     * Store blood glucose
     */
    public function storeGlucose(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'glucose_value' => 'required|numeric|min:20|max:600',
            'glucose_unit' => 'required|in:mg/dL,mmol/L',
            'glucose_context' => 'required|in:fasting_8hr,before_meal,after_meal_2hr,before_workout,after_workout,bedtime,random',
            'recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $patient = auth()->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'glucose_value' => $request->glucose_value,
            'glucose_unit' => $request->glucose_unit,
            'glucose_context' => $request->glucose_context,
            'recorded_at' => $request->recorded_at,
            'source' => 'manual',
            'device_type' => 'patient_entry',
        ]);

        // Check for alerts
        $this->checkGlucoseAlert($patient, $vital);

        return back()->with('success', 'Blood glucose recorded successfully.');
    }

    /**
     * Store temperature
     */
    public function storeTemperature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temperature' => 'required|numeric|min:30|max:45',
            'temperature_unit' => 'required|in:C,F',
            'temperature_location' => 'required|in:oral,armpit,forehead,ear,rectal',
            'recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $patient = auth()->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'temperature' => $request->temperature,
            'temperature_unit' => $request->temperature_unit,
            'temperature_location' => $request->temperature_location,
            'recorded_at' => $request->recorded_at,
            'source' => 'manual',
            'device_type' => 'patient_entry',
        ]);

        return back()->with('success', 'Temperature recorded successfully.');
    }

    /**
     * Store SpO2
     */
    public function storeSpo2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'spo2_value' => 'required|integer|min:50|max:100',
            'pr_bpm' => 'required|integer|min:30|max:250',
            'recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $patient = auth()->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'spo2_value' => $request->spo2_value,
            'pr_bpm' => $request->pr_bpm,
            'recorded_at' => $request->recorded_at,
            'source' => 'manual',
            'device_type' => 'patient_entry',
        ]);

        // Check for critical SpO2
        if ($vital->spo2_value < 90) {
            $this->createAlert($patient, 'critical', 'spo2', $vital->spo2_value, '90', 
                             "Critical SpO2 level: {$vital->spo2_value}%");
        }

        return back()->with('success', 'SpO2 recorded successfully.');
    }

    /**
     * Store weight
     */
    public function storeWeight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:10|max:500',
            'recorded_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $patient = auth()->user()->patientProfile;

        // Calculate BMI
        $bmi = null;
        if ($patient->height) {
            $heightInMeters = $patient->height / 100;
            $bmi = round($request->weight / ($heightInMeters * $heightInMeters), 2);
        }

        $vital = $patient->vitalSigns()->create([
            'weight' => $request->weight,
            'bmi' => $bmi,
            'recorded_at' => $request->recorded_at,
            'source' => 'manual',
            'device_type' => 'patient_entry',
        ]);

        return back()->with('success', 'Weight recorded successfully. BMI: ' . ($bmi ?? 'N/A'));
    }

    /**
     * Check blood pressure and create alert if needed
     */
    private function checkBloodPressureAlert($patient, $vital)
    {
        // Hypertensive crisis
        if ($vital->systolic >= 180 || $vital->diastolic >= 120) {
            $this->createAlert($patient, 'critical', 'blood_pressure', 
                             "{$vital->systolic}/{$vital->diastolic}", '180/120',
                             "Hypertensive crisis detected: {$vital->systolic}/{$vital->diastolic} mmHg");
        }
        // Stage 2 Hypertension
        elseif ($vital->systolic >= 140 || $vital->diastolic >= 90) {
            $this->createAlert($patient, 'warning', 'blood_pressure',
                             "{$vital->systolic}/{$vital->diastolic}", '140/90',
                             "Stage 2 Hypertension: {$vital->systolic}/{$vital->diastolic} mmHg");
        }
    }

    /**
     * Check glucose and create alert if needed
     */
    private function checkGlucoseAlert($patient, $vital)
    {
        if ($vital->glucose_unit === 'mg/dL') {
            if ($vital->glucose_value < 70) {
                $this->createAlert($patient, 'critical', 'glucose', $vital->glucose_value, '70',
                                 "Hypoglycemia detected: {$vital->glucose_value} mg/dL");
            } elseif ($vital->glucose_value > 200) {
                $this->createAlert($patient, 'warning', 'glucose', $vital->glucose_value, '200',
                                 "High glucose level: {$vital->glucose_value} mg/dL");
            }
        }
    }

    /**
     * Create health alert
     */
    private function createAlert($patient, $type, $parameter, $value, $threshold, $message)
    {
        $alert = $patient->healthAlerts()->create([
            'alert_type' => $type,
            'parameter' => $parameter,
            'value' => $value,
            'threshold' => $threshold,
            'message' => $message,
        ]);

        // Notify assigned clinicians
        foreach ($patient->assignedClinicians as $clinician) {
            $clinician->notifications()->create([
                'type' => 'health_alert',
                'title' => ucfirst($type) . ' Alert',
                'body' => $message,
                'priority' => $type === 'critical' ? 'urgent' : 'normal',
                'data' => ['alert_id' => $alert->id, 'patient_id' => $patient->id],
            ]);
        }
    }
}
