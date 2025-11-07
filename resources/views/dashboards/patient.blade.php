<!-- Patient Dashboard -->

<!-- Wellness Score Card -->
<div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl shadow-xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-white/80 text-sm font-medium mb-2">Your Wellness Score</p>
            <h2 class="text-5xl font-bold mb-2">{{ $wellnessScore ?? 85 }}/100</h2>
            <p class="text-white/90">You're doing great! Keep it up! 💪</p>
        </div>
        <div class="w-32 h-32">
            <canvas id="wellnessChart"></canvas>
        </div>
    </div>
</div>

<!-- Today's Summary -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Steps -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $todaySteps ?? 8547 }}</p>
        <p class="text-sm text-gray-500">Steps today</p>
        <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600" style="width: {{ min(($todaySteps ?? 8547) / 10000 * 100, 100) }}%"></div>
        </div>
    </div>

    <!-- Blood Pressure -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $latestBP ?? '120/80' }}</p>
        <p class="text-sm text-gray-500">Blood Pressure</p>
        <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Normal</span>
    </div>

    <!-- Glucose -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $latestGlucose ?? 95 }} <span class="text-sm text-gray-500">mg/dL</span></p>
        <p class="text-sm text-gray-500">Glucose Level</p>
        <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Normal</span>
    </div>

    <!-- Sleep -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $sleepHours ?? 7.5 }}h</p>
        <p class="text-sm text-gray-500">Sleep last night</p>
        <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Good</span>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Medications Today -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 font-sora">Today's Medications</h3>
                <a href="{{ route('patient.medications.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium">View All →</a>
            </div>
            
            <div class="space-y-3">
                @forelse($todayMedications ?? [] as $med)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $med->medication->name ?? 'Medication' }}</p>
                            <p class="text-sm text-gray-500">{{ $med->dosage ?? '10mg' }} - {{ $med->scheduled_time ?? '08:00' }}</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Mark Taken
                    </button>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">No medications scheduled for today</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Vital Signs Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Blood Pressure Trend (7 Days)</h3>
            <canvas id="bpChart" height="100"></canvas>
        </div>
    </div>

    <!-- Right Column -->
    <div class="space-y-6">
        <!-- Wearable Device -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Wearable Device</h3>
            
            @if(auth()->user()->patientProfile->wearable_type !== 'none')
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ ucfirst(auth()->user()->patientProfile->wearable_type) }}</p>
                    <p class="text-sm text-green-600">Connected</p>
                </div>
            </div>
            
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Last Sync</span>
                    <span class="font-medium">{{ $lastSync ?? '2 hours ago' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Battery</span>
                    <span class="font-medium text-green-600">85%</span>
                </div>
            </div>

            <button class="w-full mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark text-sm">
                Sync Now
            </button>
            @else
            <div class="text-center py-4">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mb-4">No device connected</p>
                <a href="{{ route('patient.wearables.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark text-sm inline-block">
                    Connect Device
                </a>
            </div>
            @endif
        </div>

        <!-- Health Alerts -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Health Alerts</h3>
            
            <div class="space-y-3">
                @forelse($recentAlerts ?? [] as $alert)
                <div class="flex items-start gap-3 p-3 bg-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-50 rounded-lg">
                    <svg class="w-5 h-5 text-{{ $alert->priority === 'critical' ? 'red' : 'yellow' }}-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $alert->title ?? 'Health Alert' }}</p>
                        <p class="text-xs text-gray-600">{{ $alert->message ?? 'Check your vitals' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">No alerts</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="{{ route('patient.vitals.create') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Log Vital Signs</span>
                </a>

                <a href="{{ route('patient.profile.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Edit Profile</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Wellness Chart (Doughnut)
const wellnessCtx = document.getElementById('wellnessChart').getContext('2d');
new Chart(wellnessCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [{{ $wellnessScore ?? 85 }}, {{ 100 - ($wellnessScore ?? 85) }}],
            backgroundColor: ['#ffffff', 'rgba(255, 255, 255, 0.2)'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '75%',
        plugins: { legend: { display: false }, tooltip: { enabled: false } }
    }
});

// BP Trend Chart
const bpCtx = document.getElementById('bpChart').getContext('2d');
new Chart(bpCtx, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Systolic',
            data: [118, 122, 120, 125, 119, 120, 121],
            borderColor: '#863588',
            backgroundColor: 'rgba(134, 53, 136, 0.1)',
            tension: 0.4
        }, {
            label: 'Diastolic',
            data: [78, 82, 80, 84, 79, 80, 81],
            borderColor: '#0097a7',
            backgroundColor: 'rgba(0, 151, 167, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: false, min: 60, max: 140 } }
    }
});
</script>
@endpush
