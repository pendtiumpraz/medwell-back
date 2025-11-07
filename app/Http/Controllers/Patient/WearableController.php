<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WearableController extends Controller
{
    /**
     * Display wearable connection status
     */
    public function index()
    {
        $patient = auth()->user()->patientProfile;
        return view('patient.wearables.index', compact('patient'));
    }

    /**
     * Connect Fitbit (OAuth)
     */
    public function connectFitbit()
    {
        $clientId = config('services.fitbit.client_id');
        $redirectUri = route('patient.wearables.fitbit.callback');
        
        $authUrl = "https://www.fitbit.com/oauth2/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'activity heartrate sleep weight profile',
        ]);

        return redirect($authUrl);
    }

    /**
     * Fitbit OAuth callback
     */
    public function fitbitCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('patient.wearables.index')
                           ->with('error', 'Fitbit authorization failed.');
        }

        $code = $request->code;

        // Exchange code for tokens
        $response = Http::asForm()->post('https://api.fitbit.com/oauth2/token', [
            'client_id' => config('services.fitbit.client_id'),
            'client_secret' => config('services.fitbit.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => route('patient.wearables.fitbit.callback'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $patient = auth()->user()->patientProfile;
            $patient->update([
                'wearable_type' => 'fitbit',
                'fitbit_user_id' => $data['user_id'],
                'fitbit_access_token' => encrypt($data['access_token']),
                'fitbit_refresh_token' => encrypt($data['refresh_token']),
                'fitbit_token_expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            // Initial sync
            $this->syncFitbitData($patient);

            return redirect()->route('patient.wearables.index')
                           ->with('success', 'Fitbit connected successfully!');
        }

        return redirect()->route('patient.wearables.index')
                       ->with('error', 'Failed to connect Fitbit.');
    }

    /**
     * Connect Huawei (OAuth)
     */
    public function connectHuawei()
    {
        $clientId = config('services.huawei.client_id');
        $redirectUri = route('patient.wearables.huawei.callback');
        
        $authUrl = "https://oauth-login.cloud.huawei.com/oauth2/v3/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'https://www.huawei.com/healthkit/activity.read https://www.huawei.com/healthkit/heartrate.read',
            'state' => csrf_token(),
        ]);

        return redirect($authUrl);
    }

    /**
     * Huawei OAuth callback
     */
    public function huaweiCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('patient.wearables.index')
                           ->with('error', 'Huawei authorization failed.');
        }

        $code = $request->code;

        // Exchange code for tokens
        $response = Http::asForm()->post('https://oauth-login.cloud.huawei.com/oauth2/v3/token', [
            'client_id' => config('services.huawei.client_id'),
            'client_secret' => config('services.huawei.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => route('patient.wearables.huawei.callback'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $patient = auth()->user()->patientProfile;
            $patient->update([
                'wearable_type' => 'huawei',
                'huawei_access_token' => encrypt($data['access_token']),
                'huawei_refresh_token' => encrypt($data['refresh_token']),
                'huawei_token_expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            return redirect()->route('patient.wearables.index')
                           ->with('success', 'Huawei Health connected successfully!');
        }

        return redirect()->route('patient.wearables.index')
                       ->with('error', 'Failed to connect Huawei Health.');
    }

    /**
     * Manual sync wearable data
     */
    public function sync()
    {
        $patient = auth()->user()->patientProfile;

        if ($patient->wearable_type === 'fitbit') {
            $this->syncFitbitData($patient);
        } elseif ($patient->wearable_type === 'huawei') {
            $this->syncHuaweiData($patient);
        } else {
            return back()->with('error', 'No wearable device connected.');
        }

        return back()->with('success', 'Wearable data synced successfully!');
    }

    /**
     * Connect wearable (generic)
     */
    public function connect($type)
    {
        $patient = auth()->user()->patientProfile;
        
        $patient->update([
            'wearable_type' => $type,
            'wearable_connected_at' => now(),
            'wearable_sync_enabled' => true,
        ]);
        
        return redirect()->route('patient.wearables.index')
            ->with('success', ucfirst($type) . ' connected successfully!');
    }

    /**
     * Disconnect wearable
     */
    public function disconnect()
    {
        $patient = auth()->user()->patientProfile;

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

        return back()->with('success', 'Wearable device disconnected.');
    }

    /**
     * Sync Fitbit data
     */
    private function syncFitbitData($patient)
    {
        $accessToken = decrypt($patient->fitbit_access_token);
        $date = today()->format('Y-m-d');

        // Get activity data
        $activity = Http::withToken($accessToken)
                       ->get("https://api.fitbit.com/1/user/-/activities/date/{$date}.json");

        // Get heart rate data
        $heartRate = Http::withToken($accessToken)
                        ->get("https://api.fitbit.com/1/user/-/activities/heart/date/{$date}/1d.json");

        // Get sleep data
        $sleep = Http::withToken($accessToken)
                    ->get("https://api.fitbit.com/1.2/user/-/sleep/date/{$date}.json");

        if ($activity->successful() && $heartRate->successful() && $sleep->successful()) {
            $activityData = $activity->json();
            $heartData = $heartRate->json();
            $sleepData = $sleep->json();

            // Save to wearable_daily_summary
            $patient->wearableData()->updateOrCreate(
                ['date' => $date],
                [
                    'steps' => $activityData['summary']['steps'] ?? 0,
                    'distance' => $activityData['summary']['distances'][0]['distance'] ?? 0,
                    'floors_climbed' => $activityData['summary']['floors'] ?? 0,
                    'active_minutes' => $activityData['summary']['fairlyActiveMinutes'] + $activityData['summary']['veryActiveMinutes'],
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

    /**
     * Sync Huawei data (aggregated only)
     */
    private function syncHuaweiData($patient)
    {
        $accessToken = decrypt($patient->huawei_access_token);
        
        // Huawei Health Kit API (aggregated data)
        $response = Http::withToken($accessToken)
                       ->get('https://health-api.cloud.huawei.com/healthkit/v1/activityRecords/summary', [
                           'startTime' => today()->startOfDay()->timestamp * 1000,
                           'endTime' => today()->endOfDay()->timestamp * 1000,
                       ]);

        if ($response->successful()) {
            $data = $response->json();

            $patient->wearableData()->updateOrCreate(
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
        }
    }
}
