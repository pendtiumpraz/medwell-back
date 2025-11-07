<!-- Admin Dashboard -->

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Patients -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Total Patients</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_patients'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">+{{ $stats['new_patients_this_month'] ?? 0 }} this month</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Clinicians -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Active Clinicians</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_clinicians'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">{{ $stats['online_clinicians'] ?? 0 }} online now</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Critical Alerts -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Critical Alerts</p>
                <h3 class="text-3xl font-bold">{{ $stats['critical_alerts'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">Needs immediate attention</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Wearables Connected -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Wearables Connected</p>
                <h3 class="text-3xl font-bold">{{ $stats['wearables_connected'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">{{ round(($stats['wearables_connected'] ?? 0) / max($stats['total_patients'] ?? 1, 1) * 100) }}% adoption</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Patient Growth Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Patient Growth</h3>
        <canvas id="patientGrowthChart" height="200"></canvas>
    </div>

    <!-- Wearable Distribution -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Wearable Device Distribution</h3>
        <canvas id="wearableChart" height="200"></canvas>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Patients -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 font-sora">Recent Patients</h3>
            <a href="{{ route('admin.patients.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium">View All →</a>
        </div>
        
        <div class="space-y-3">
            @forelse($recentPatients ?? [] as $patient)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($patient->full_name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $patient->full_name }}</p>
                        <p class="text-xs text-gray-500">Joined {{ $patient->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark text-sm font-medium">
                    View →
                </a>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">No recent patients</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Actions</h3>
        
        <div class="space-y-3">
            <a href="{{ route('admin.patients.index') }}" class="flex items-center gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors group">
                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center group-hover:bg-primary/20">
                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Add New Patient</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors group">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200">
                    <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Manage Users</span>
            </a>

            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors group">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Roles & Permissions</span>
            </a>

            <a href="#" class="flex items-center gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors group">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200">
                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">View Reports</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Patient Growth Chart
const patientCtx = document.getElementById('patientGrowthChart').getContext('2d');
new Chart(patientCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'New Patients',
            data: [12, 19, 25, 32, 45, 58],
            borderColor: '#863588',
            backgroundColor: 'rgba(134, 53, 136, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Wearable Distribution Chart
const wearableCtx = document.getElementById('wearableChart').getContext('2d');
new Chart(wearableCtx, {
    type: 'doughnut',
    data: {
        labels: ['Fitbit', 'Huawei', 'Apple Watch', 'Samsung', 'Not Connected'],
        datasets: [{
            data: [30, 25, 20, 15, 10],
            backgroundColor: ['#4CAF50', '#FF5722', '#000000', '#2196F3', '#9E9E9E']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush
