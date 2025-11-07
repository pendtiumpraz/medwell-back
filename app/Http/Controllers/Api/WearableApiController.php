<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WearableApiController extends Controller
{
    /**
     * Get wearable connection status
     */
    public function status(Request $request)
    {
        $patient = $request->user()->patientProfile;

        return response()->json([
            'success' => true,
            'data' => [
                'wearable_type' => $patient->wearable_type,
                'connected' => $patient->hasWearableConnected(),
                'needs_refresh' => $patient->needsTokenRefresh(),
                'last_synced' => $patient->wearableData()->latest('last_synced_at')->value('last_synced_at'),
            ]
        ]);
    }

    /**
     * Sync Apple Watch data (via HealthKit on device)
     */
    public function syncApple(Request $request)
    {
        $patient = $request->user()->patientProfile;

        // Apple Watch syncs via HealthKit on device
        // Mobile app sends aggregated data
        $patient->wearableData()->updateOrCreate(
            ['date' => today()],
            [
                'steps' => $request->steps ?? 0,
                'distance' => $request->distance ?? 0,
                'floors_climbed' => $request->floors ?? 0,
                'active_minutes' => $request->active_minutes ?? 0,
                'calories_burned' => $request->calories ?? 0,
                'resting_heart_rate' => $request->resting_hr,
                'avg_heart_rate' => $request->avg_hr,
                'max_heart_rate' => $request->max_hr,
                'sleep_duration' => $request->sleep_duration,
                'avg_spo2' => $request->avg_spo2,
                'wellness_score' => $request->wellness_score,
                'last_synced_at' => now(),
                'device_type' => 'apple',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Apple Watch data synced',
        ]);
    }

    /**
     * Trigger Fitbit sync
     */
    public function syncFitbit(Request $request)
    {
        $patient = $request->user()->patientProfile;

        if ($patient->wearable_type !== 'fitbit') {
            return response()->json([
                'success' => false,
                'message' => 'Fitbit not connected'
            ], 400);
        }

        // Refresh token if needed
        if ($patient->needsTokenRefresh()) {
            $this->refreshFitbitToken($patient);
        }

        $this->syncFitbitData($patient);

        return response()->json([
            'success' => true,
            'message' => 'Fitbit data synced',
            'data' => $patient->todayWearableData()->first(),
        ]);
    }

    /**
     * Trigger Huawei sync
     */
    public function syncHuawei(Request $request)
    {
        $patient = $request->user()->patientProfile;

        if ($patient->wearable_type !== 'huawei') {
            return response()->json([
                'success' => false,
                'message' => 'Huawei not connected'
            ], 400);
        }

        // Sync logic (similar to web controller)
        $accessToken = decrypt($patient->huawei_access_token);
        
        $response = Http::withToken($accessToken)
                       ->get('https://health-api.cloud.huawei.com/healthkit/v1/activityRecords/summary', [
                           'startTime' => today()->startOfDay()->timestamp * 1000,
                           'endTime' => today()->endOfDay()->timestamp * 1000,
                       ]);

        if ($response->successful()) {
            $data = $response->json();

            $summary = $patient->wearableData()->updateOrCreate(
                ['date' => today()],
                [
                    'steps' => $data['steps'] ?? 0,
                    'distance' => $data['distance'] ?? 0,
                    'calories_burned' => $data['calories'] ?? 0,
                    'active_minutes' => $data['activeMinutes'] ?? 0,
                    'last_synced_at' => now(),
                    'device_type' => 'huawei',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Huawei data synced',
                'data' => $summary,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to sync Huawei data'
        ], 500);
    }

    /**
     * Disconnect wearable
     */
    public function disconnect(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $patient->update([
            'wearable_type' => 'none',
            'fitbit_user_id' => null,
            'fitbit_access_token' => null,
            'fitbit_refresh_token' => null,
            'fitbit_token_expires_at' => null,
            'huawei_access_token' => null,
            'huawei_refresh_token' => null,
            'huawei_token_expires_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wearable disconnected'
        ]);
    }

    /**
     * Get wearable data
     */
    public function getData(Request $request)
    {
        $patient = $request->user()->patientProfile;

        $query = $patient->wearableData();

        if ($request->filled('from')) {
            $query->where('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('date', '<=', $request->to);
        }

        $data = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get today's wearable data
     */
    public function getTodayData(Request $request)
    {
        $patient = $request->user()->patientProfile;
        $data = $patient->todayWearableData()->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Refresh Fitbit token
     */
    private function refreshFitbitToken($patient)
    {
        $refreshToken = decrypt($patient->fitbit_refresh_token);

        $response = Http::asForm()->post('https://api.fitbit.com/oauth2/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('services.fitbit.client_id'),
            'client_secret' => config('services.fitbit.client_secret'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $patient->update([
                'fitbit_access_token' => encrypt($data['access_token']),
                'fitbit_refresh_token' => encrypt($data['refresh_token']),
                'fitbit_token_expires_at' => now()->addSeconds($data['expires_in']),
            ]);
        }
    }

    /**
     * Sync Fitbit data (duplicate from web controller for API use)
     */
    private function syncFitbitData($patient)
    {
        $accessToken = decrypt($patient->fitbit_access_token);
        $date = today()->format('Y-m-d');

        $activity = Http::withToken($accessToken)->get("https://api.fitbit.com/1/user/-/activities/date/{$date}.json");
        $heartRate = Http::withToken($accessToken)->get("https://api.fitbit.com/1/user/-/activities/heart/date/{$date}/1d.json");
        $sleep = Http::withToken($accessToken)->get("https://api.fitbit.com/1.2/user/-/sleep/date/{$date}.json");

        if ($activity->successful() && $heartRate->successful() && $sleep->successful()) {
            $activityData = $activity->json();
            $heartData = $heartRate->json();
            $sleepData = $sleep->json();

            $patient->wearableData()->updateOrCreate(
                ['date' => $date],
                [
                    'steps' => $activityData['summary']['steps'] ?? 0,
                    'distance' => $activityData['summary']['distances'][0]['distance'] ?? 0,
                    'floors_climbed' => $activityData['summary']['floors'] ?? 0,
                    'active_minutes' => ($activityData['summary']['fairlyActiveMinutes'] ?? 0) + ($activityData['summary']['veryActiveMinutes'] ?? 0),
                    'calories_burned' => $activityData['summary']['caloriesOut'] ?? 0,
                    'resting_heart_rate' => $heartData['activities-heart'][0]['value']['restingHeartRate'] ?? null,
                    'sleep_duration' => $sleepData['summary']['totalMinutesAsleep'] ?? 0,
                    'deep_sleep' => $sleepData['summary']['stages']['deep'] ?? 0,
                    'light_sleep' => $sleepData['summary']['stages']['light'] ?? 0,
                    'rem_sleep' => $sleepData['summary']['stages']['rem'] ?? 0,
                    'last_synced_at' => now(),
                    'device_type' => 'fitbit',
                ]
            );
        }
    }
}
