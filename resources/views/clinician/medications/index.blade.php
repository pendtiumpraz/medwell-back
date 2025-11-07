@extends('layouts.app')

@section('title', 'Patient Medications')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Medications - {{ $patient->full_name }}</h1>
            <p class="text-gray-600 mt-1">Manage prescriptions for this patient</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('clinician.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
                ← Back to Patient
            </a>
            <a href="{{ route('clinician.medications.create', $patient->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                + Prescribe New
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <p class="text-blue-600 text-sm font-medium">Active</p>
        <p class="text-3xl font-bold text-blue-700">{{ $medications->where('status', 'active')->count() }}</p>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <p class="text-yellow-600 text-sm font-medium">Pending Consent</p>
        <p class="text-3xl font-bold text-yellow-700">{{ $medications->where('consent_status', 'pending')->count() }}</p>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <p class="text-green-600 text-sm font-medium">Accepted</p>
        <p class="text-3xl font-bold text-green-700">{{ $medications->where('consent_status', 'accepted')->count() }}</p>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-red-600 text-sm font-medium">Declined</p>
        <p class="text-3xl font-bold text-red-700">{{ $medications->where('consent_status', 'declined')->count() }}</p>
    </div>
</div>

<!-- Medications List -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800 font-sora">Prescription History</h3>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($medications as $med)
        <div class="p-6 hover:bg-gray-50" x-data="{ showEdit: false }">
            <div class="flex items-start justify-between">
                <!-- Medication Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 class="text-lg font-bold text-gray-800">{{ $med->medication->name }}</h4>
                        
                        <!-- Status Badges -->
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $med->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $med->status === 'paused' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $med->status === 'discontinued' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            {{ ucfirst($med->status) }}
                        </span>

                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $med->consent_status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $med->consent_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $med->consent_status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            Consent: {{ ucfirst($med->consent_status) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-3">{{ $med->medication->generic_name }}</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Dosage</p>
                            <p class="font-semibold">{{ $med->dosage }} {{ $med->dosage_unit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Frequency</p>
                            <p class="font-semibold">{{ $med->frequency }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Duration</p>
                            <p class="font-semibold">
                                {{ $med->start_date->format('M d') }} - 
                                {{ $med->end_date ? $med->end_date->format('M d, Y') : 'Ongoing' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Prescribed</p>
                            <p class="font-semibold">{{ $med->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($med->instructions)
                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-700"><strong>Instructions:</strong> {{ $med->instructions }}</p>
                    </div>
                    @endif

                    @if($med->prescriber_notes)
                    <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $med->prescriber_notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="ml-4 flex flex-col gap-2">
                    @if($med->status === 'active')
                    <button @click="showEdit = !showEdit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('clinician.medications.pause', $med->id) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            Pause
                        </button>
                    </form>
                    <form method="POST" action="{{ route('clinician.medications.discontinue', $med->id) }}" onsubmit="return confirm('Discontinue this medication?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Discontinue
                        </button>
                    </form>
                    @elseif($med->status === 'paused')
                    <form method="POST" action="{{ route('clinician.medications.update', $med->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Resume
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Edit Form -->
            <div x-show="showEdit" class="mt-6 p-4 border-2 border-blue-200 rounded-lg bg-blue-50">
                <h5 class="font-bold text-gray-800 mb-4">Edit Prescription</h5>
                <form method="POST" action="{{ route('clinician.medications.update', $med->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dosage</label>
                            <input type="number" name="dosage" value="{{ $med->dosage }}" required step="0.1" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Frequency</label>
                            <input type="text" name="frequency" value="{{ $med->frequency }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Times per Day</label>
                            <input type="number" name="times_per_day" value="{{ $med->times_per_day }}" required min="1" max="10" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Instructions</label>
                        <textarea name="instructions" rows="2" class="w-full px-3 py-2 border rounded-lg">{{ $med->instructions }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                            Save Changes
                        </button>
                        <button type="button" @click="showEdit = false" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-gray-500 text-lg">No medications prescribed yet</p>
            <a href="{{ route('clinician.medications.create', $patient->id) }}" class="inline-block mt-4 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark">
                Prescribe First Medication
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
