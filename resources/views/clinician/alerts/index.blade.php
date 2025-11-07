@extends('layouts.app')

@section('title', 'Health Alerts')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">Health Alerts</h1>
    <p class="text-gray-600 mt-1">Monitor and respond to patient health alerts</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-red-600 text-sm font-medium">Critical</p>
        <p class="text-3xl font-bold text-red-700">{{ $stats['critical'] ?? 0 }}</p>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
        <p class="text-orange-600 text-sm font-medium">High</p>
        <p class="text-3xl font-bold text-orange-700">{{ $stats['high'] ?? 0 }}</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <p class="text-yellow-600 text-sm font-medium">Medium</p>
        <p class="text-3xl font-bold text-yellow-700">{{ $stats['medium'] ?? 0 }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <p class="text-blue-600 text-sm font-medium">Low</p>
        <p class="text-3xl font-bold text-blue-700">{{ $stats['low'] ?? 0 }}</p>
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex gap-8 px-6" aria-label="Tabs">
            <a href="?filter=unresolved" class="py-4 px-1 border-b-2 {{ request('filter', 'unresolved') === 'unresolved' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Unresolved ({{ $stats['unresolved'] ?? 0 }})
            </a>
            <a href="?filter=all" class="py-4 px-1 border-b-2 {{ request('filter') === 'all' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                All Alerts
            </a>
            <a href="?filter=resolved" class="py-4 px-1 border-b-2 {{ request('filter') === 'resolved' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Resolved
            </a>
        </nav>
    </div>
</div>

<!-- Alerts List -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="divide-y divide-gray-200">
        @forelse($alerts as $alert)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between">
                <!-- Alert Info -->
                <div class="flex items-start gap-4 flex-1">
                    <!-- Priority Badge -->
                    <div class="flex-shrink-0">
                        @if($alert->priority === 'critical')
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @elseif($alert->priority === 'high')
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Alert Details -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-bold text-gray-800">{{ $alert->patient->full_name }}</h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $alert->priority === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $alert->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $alert->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $alert->priority === 'low' ? 'bg-blue-100 text-blue-800' : '' }}
                            ">
                                {{ ucfirst($alert->priority) }}
                            </span>
                        </div>
                        
                        <p class="text-gray-800 font-medium mb-1">{{ $alert->alert_type }}: {{ $alert->title }}</p>
                        <p class="text-gray-600 text-sm mb-2">{{ $alert->message }}</p>
                        
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>{{ $alert->created_at->diffForHumans() }}</span>
                            @if($alert->acknowledged_at)
                                <span class="text-green-600">✓ Acknowledged</span>
                            @endif
                            @if($alert->resolved_at)
                                <span class="text-blue-600">✓ Resolved</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 ml-4">
                    @if(!$alert->acknowledged_at)
                    <form method="POST" action="{{ route('clinician.alerts.acknowledge', $alert->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Acknowledge
                        </button>
                    </form>
                    @endif
                    
                    @if($alert->acknowledged_at && !$alert->resolved_at)
                    <form method="POST" action="{{ route('clinician.alerts.resolve', $alert->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Resolve
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('clinician.patients.show', $alert->patient_id) }}" 
                       class="px-4 py-2 text-sm text-primary hover:bg-primary/5 rounded-lg">
                        View Patient
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500">No alerts to display</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alerts->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $alerts->links() }}
    </div>
    @endif
</div>
@endsection
