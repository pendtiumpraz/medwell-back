@extends('layouts.app')

@section('title', 'Prescribe Medication')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Prescribe Medication</h1>
            <p class="text-gray-600 mt-1">For patient: <strong>{{ $patient->full_name }}</strong></p>
        </div>
        <a href="{{ route('clinician.patients.show', $patient->id) }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
            ← Back to Patient
        </a>
    </div>
</div>

<form method="POST" action="{{ route('clinician.medications.store', $patient->id) }}" class="bg-white rounded-xl shadow-sm p-6">
    @csrf

    <!-- Medication Selection -->
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Medication *</label>
        <select name="medication_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
            <option value="">Select Medication</option>
            @foreach($medications as $med)
            <option value="{{ $med->id }}">
                {{ $med->name }} ({{ $med->generic_name }}) - {{ implode(', ', json_decode($med->strengths, true)) }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Dosage -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Dosage *</label>
            <input type="number" name="dosage" required step="0.1" min="0.1" placeholder="e.g., 10" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Dosage Unit *</label>
            <select name="dosage_unit" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                <option value="mg">mg (milligrams)</option>
                <option value="g">g (grams)</option>
                <option value="mcg">mcg (micrograms)</option>
                <option value="mL">mL (milliliters)</option>
                <option value="IU">IU (International Units)</option>
                <option value="tablet">tablet</option>
                <option value="capsule">capsule</option>
            </select>
        </div>
    </div>

    <!-- Frequency -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Frequency *</label>
            <input type="text" name="frequency" required placeholder="e.g., Twice daily" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Times per Day *</label>
            <input type="number" name="times_per_day" required min="1" max="10" placeholder="1-10" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <!-- Times -->
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Scheduled Times *</label>
        <div id="times-container" class="space-y-2">
            <div class="flex gap-2">
                <input type="time" name="times[]" required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" value="08:00">
                <button type="button" onclick="addTimeSlot()" class="px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">+</button>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Add time slots when patient should take this medication</p>
    </div>

    <!-- Duration -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
            <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date (Optional)</label>
            <input type="date" name="end_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        </div>
    </div>

    <!-- Instructions -->
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Instructions for Patient</label>
        <textarea name="instructions" rows="3" placeholder="e.g., Take with food, avoid alcohol" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"></textarea>
    </div>

    <!-- Prescriber Notes -->
    <div class="mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Prescriber Notes (Internal)</label>
        <textarea name="prescriber_notes" rows="3" placeholder="Notes for other clinicians" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"></textarea>
    </div>

    <!-- Consent Warning -->
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-yellow-800">Patient Consent Required</p>
                <p class="text-sm text-yellow-700 mt-1">This prescription will be sent to the patient for consent. Patient must accept before medication schedule is activated.</p>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="flex gap-3">
        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark font-semibold">
            Prescribe Medication
        </button>
        <a href="{{ route('clinician.patients.show', $patient->id) }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
            Cancel
        </a>
    </div>
</form>

@push('scripts')
<script>
function addTimeSlot() {
    const container = document.getElementById('times-container');
    const newSlot = document.createElement('div');
    newSlot.className = 'flex gap-2';
    newSlot.innerHTML = `
        <input type="time" name="times[]" required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
        <button type="button" onclick="this.parentElement.remove()" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">−</button>
    `;
    container.appendChild(newSlot);
}
</script>
@endpush
@endsection
