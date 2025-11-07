<!-- Clinician Dashboard -->

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">My Patients</p>
                <h3 class="text-3xl font-bold">{{ $stats['my_patients'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">Under your care</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Unresolved Alerts</p>
                <h3 class="text-3xl font-bold">{{ $stats['unresolved_alerts'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">{{ $stats['critical_alerts'] ?? 0 }} critical</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Appointments Today</p>
                <h3 class="text-3xl font-bold">{{ $stats['appointments_today'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">{{ $stats['completed_today'] ?? 0 }} completed</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">Unread Messages</p>
                <h3 class="text-3xl font-bold">{{ $stats['unread_messages'] ?? 0 }}</h3>
                <p class="text-white/70 text-xs mt-2">Requires response</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3.293 3.293 3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Alerts -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 font-sora">Recent Alerts</h3>
            <span class="text-gray-400 text-sm font-medium">Coming Soon</span>
        </div>
        
        <div class="space-y-3">
            @forelse($recentAlerts ?? [] as $alert)
            <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg">
                <div class="w-2 h-2 rounded-full {{ $alert->priority === 'critical' ? 'bg-red-500' : 'bg-orange-500' }}"></div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $alert->patient->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $alert->title }}</p>
                </div>
                <span class="text-xs text-gray-400">{{ $alert->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">No recent alerts</p>
            @endforelse
        </div>
    </div>

    <!-- High Priority Patients -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 font-sora">High Priority Patients</h3>
            <a href="{{ route('clinician.patients.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium">View All →</a>
        </div>
        
        <div class="space-y-3">
            @forelse($priorityPatients ?? [] as $patient)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr($patient->full_name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->unresolvedAlertsCount ?? 0 }} alerts</p>
                    </div>
                </div>
                <a href="{{ route('clinician.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark text-sm font-medium">
                    View →
                </a>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">No priority patients</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Quick Actions</h3>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('clinician.patients.index') }}" class="flex flex-col items-center gap-3 p-4 hover:bg-gray-50 rounded-lg transition-colors group">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200">
                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">My Patients</span>
        </a>

        <div class="flex flex-col items-center gap-3 p-4 bg-gray-50 rounded-lg opacity-50 cursor-not-allowed">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">View Alerts</span>
        </a>

        <div class="flex flex-col items-center gap-3 p-4 bg-gray-50 rounded-lg opacity-50 cursor-not-allowed">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3.293 3.293 3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Messages</span>
        </a>

        <a href="#" class="flex flex-col items-center gap-3 p-4 hover:bg-gray-50 rounded-lg transition-colors group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-gray-700 text-center">Schedule</span>
        </a>
    </div>
</div>
