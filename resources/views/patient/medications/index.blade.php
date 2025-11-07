@extends('layouts.app')

@section('title', 'My Medications')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 font-sora">My Medications</h1>
    <p class="text-gray-600 mt-1">Manage your prescriptions and medication schedule</p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <p class="text-white/80 text-sm font-medium mb-1">Active Medications</p>
        <p class="text-3xl font-bold">{{ $stats['active'] ?? 0 }}</p>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <p class="text-white/80 text-sm font-medium mb-1">Today's Doses</p>
        <p class="text-3xl font-bold">{{ $stats['today'] ?? 0 }}</p>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <p class="text-white/80 text-sm font-medium mb-1">Adherence Rate</p>
        <p class="text-3xl font-bold">{{ $stats['adherence'] ?? 95 }}%</p>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <p class="text-white/80 text-sm font-medium mb-1">Pending Consent</p>
        <p class="text-3xl font-bold">{{ $stats['pending_consent'] ?? 0 }}</p>
    </div>
</div>

<!-- Pending Consent Medications -->
@if($pendingConsentMeds && $pendingConsentMeds->count() > 0)
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
    <div class="flex items-start gap-3 mb-4">
        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div>
            <h3 class="text-lg font-bold text-yellow-800">Medications Awaiting Your Consent</h3>
            <p class="text-sm text-yellow-700 mt-1">Your doctor has prescribed new medications that require your approval</p>
        </div>
    </div>

    <div class="space-y-3">
        @foreach($pendingConsentMeds as $med)
        <div class="bg-white rounded-lg p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h4 class="font-bold text-gray-800">{{ $med->medication->name }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $med->dosage }} - {{ $med->frequency }}</p>
                    <p class="text-sm text-gray-500 mt-2">Prescribed by: Dr. {{ $med->prescribedBy->username }}</p>
                    @if($med->notes)
                    <p class="text-sm text-gray-600 mt-2 p-2 bg-gray-50 rounded">{{ $med->notes }}</p>
                    @endif
                </div>
                
                <div class="flex gap-2 ml-4">
                    <form method="POST" action="{{ route('patient.medications.consent', $med->id) }}">
                        @csrf
                        <input type="hidden" name="decision" value="accepted">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('patient.medications.consent', $med->id) }}">
                        @csrf
                        <input type="hidden" name="decision" value="declined">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            Decline
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Today's Schedule -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 font-sora">Today's Medication Schedule</h3>
    
    <div class="space-y-3">
        @forelse($todaySchedule ?? [] as $schedule)
        <div class="flex items-center justify-between p-4 border-2 {{ $schedule->taken_at ? 'border-green-200 bg-green-50' : 'border-gray-200' }} rounded-lg">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $schedule->taken_at ? 'bg-green-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center">
                    @if($schedule->taken_at)
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    @endif
                </div>
                
                <div>
                    <p class="font-bold text-gray-800">{{ $schedule->patientMedication->medication->name }}</p>
                    <p class="text-sm text-gray-600">{{ $schedule->patientMedication->dosage }}</p>
                    <p class="text-sm text-gray-500">{{ $schedule->scheduled_time }}</p>
                </div>
            </div>

            @if(!$schedule->taken_at)
            <form method="POST" action="{{ route('patient.medications.mark-taken', $schedule->id) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Mark as Taken
                </button>
            </form>
            @else
            <span class="text-green-600 text-sm font-medium">
                Taken at {{ $schedule->taken_at->format('g:i A') }}
            </span>
            @endif
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

<!-- All Active Medications -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 font-sora">All Active Medications</h3>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($medications as $patientMed)
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 class="text-lg font-bold text-gray-800">{{ $patientMed->medication->name }}</h4>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $patientMed->consent_status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $patientMed->consent_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $patientMed->consent_status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($patientMed->consent_status) }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-2">{{ $patientMed->medication->generic_name }}</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Dosage</p>
                            <p class="font-semibold text-gray-800">{{ $patientMed->dosage }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Frequency</p>
                            <p class="font-semibold text-gray-800">{{ $patientMed->frequency }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Start Date</p>
                            <p class="font-semibold text-gray-800">{{ $patientMed->start_date->format('M d, Y') }}</p>
                        </div>
                        @if($patientMed->end_date)
                        <div>
                            <p class="text-gray-500">End Date</p>
                            <p class="font-semibold text-gray-800">{{ $patientMed->end_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>

                    @if($patientMed->notes)
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">{{ $patientMed->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Adherence Progress -->
            <div>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Adherence Rate</span>
                    <span class="font-semibold text-gray-800">{{ $patientMed->adherence_rate ?? 95 }}%</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-600 rounded-full" style="width: {{ $patientMed->adherence_rate ?? 95 }}%"></div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <p class="text-gray-500 text-lg">No medications prescribed yet</p>
        </div>
        @endforelse
    </div>

    @if($medications->hasPages())
    <div class="p-6 border-t">
        {{ $medications->links() }}
    </div>
    @endif
</div>
@endsection
