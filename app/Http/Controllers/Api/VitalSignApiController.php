<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VitalSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VitalSignApiController extends Controller
{
    /**
     * Store blood pressure
     */
    public function storeBloodPressure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'systolic' => 'required|integer|min:50|max:300',
            'diastolic' => 'required|integer|min:30|max:200',
            'pulse' => 'required|integer|min:30|max:250',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'pulse' => $request->pulse,
            'recorded_at' => $request->recorded_at ?? now(),
            'source' => 'manual',
            'device_type' => 'mobile_app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blood pressure recorded',
            'data' => [
                'vital' => $vital,
                'status' => $vital->getBloodPressureStatus(),
            ]
        ], 201);
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
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'glucose_value' => $request->glucose_value,
            'glucose_unit' => $request->glucose_unit,
            'glucose_context' => $request->glucose_context,
            'recorded_at' => $request->recorded_at ?? now(),
            'source' => 'manual',
            'device_type' => 'mobile_app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blood glucose recorded',
            'data' => [
                'vital' => $vital,
                'status' => $vital->getGlucoseStatus(),
            ]
        ], 201);
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
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'temperature' => $request->temperature,
            'temperature_unit' => $request->temperature_unit,
            'temperature_location' => $request->temperature_location,
            'recorded_at' => $request->recorded_at ?? now(),
            'source' => 'manual',
            'device_type' => 'mobile_app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Temperature recorded',
            'data' => $vital
        ], 201);
    }

    /**
     * Store SpO2
     */
    public function storeSpo2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'spo2_value' => 'required|integer|min:50|max:100',
            'pr_bpm' => 'required|integer|min:30|max:250',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;

        $vital = $patient->vitalSigns()->create([
            'spo2_value' => $request->spo2_value,
            'pr_bpm' => $request->pr_bpm,
            'recorded_at' => $request->recorded_at ?? now(),
            'source' => 'manual',
            'device_type' => 'mobile_app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SpO2 recorded',
            'data' => $vital
        ], 201);
    }

    /**
     * Store weight
     */
    public function storeWeight(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric|min:10|max:500',
            'recorded_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient = $request->user()->patientProfile;

        // Calculate BMI if height available
        $bmi = null;
        if ($patient->height) {
            $heightInMeters = $patient->height / 100;
            $bmi = round($request->weight / ($heightInMeters * $heightInMeters), 2);
        }

        $vital = $patient->vitalSigns()->create([
            'weight' => $request->weight,
            'bmi' => $bmi,
            'recorded_at' => $request->recorded_at ?? now(),
            'source' => 'manual',
            'device_type' => 'mobile_app',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Weight recorded',
            'data' => [
                'vital' => $vital,
                'bmi' => $bmi,
            ]
        ], 201);
    }

    /**
     * Get vital history
     */
    public function history(Request $request)
    {
        $patient = $request->user()->patientProfile;

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

        $vitals = $query->orderBy('recorded_at', 'desc')
                       ->paginate($request->per_page ?? 50);

        return response()->json([
            'success' => true,
            'data' => $vitals
        ]);
    }

    /**
     * Get vital trends
     */
    public function trends(Request $request)
    {
        $patient = $request->user()->patientProfile;
        $type = $request->input('type', 'blood_pressure');
        $days = $request->input('days', 30);

        $vitals = $patient->vitalSigns()
                         ->byType($type)
                         ->where('recorded_at', '>=', now()->subDays($days))
                         ->orderBy('recorded_at')
                         ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'type' => $type,
                'days' => $days,
                'vitals' => $vitals,
            ]
        ]);
    }

    /**
     * Get latest vitals
     */
    public function latest(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $latest = [
            'blood_pressure' => $patient->vitalSigns()->byType('blood_pressure')->latest('recorded_at')->first(),
            'glucose' => $patient->vitalSigns()->byType('glucose')->latest('recorded_at')->first(),
            'temperature' => $patient->vitalSigns()->byType('temperature')->latest('recorded_at')->first(),
            'spo2' => $patient->vitalSigns()->byType('spo2')->latest('recorded_at')->first(),
            'weight' => $patient->vitalSigns()->byType('weight')->latest('recorded_at')->first(),
        ];

        return response()->json([
            'success' => true,
            'data' => $latest
        ]);
    }
}
