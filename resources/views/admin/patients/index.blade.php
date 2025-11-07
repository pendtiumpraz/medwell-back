@extends('layouts.app')

@section('title', 'Patients Management')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="patientManagement()">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-sora">Patients Management</h1>
            <p class="text-gray-600 mt-1">Manage all patients in your organization</p>
        </div>
        <button 
            @click="openCreate()"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-lg"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Add Patient
        </button>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.patients.index') }}">
            <!-- Header with Show Deleted -->
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-primary"></i>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Filter Patients</h3>
                </div>
                
                <label class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-100 transition-colors">
                    <input 
                        type="checkbox" 
                        name="show_deleted" 
                        value="1" 
                        {{ request('show_deleted') ? 'checked' : '' }}
                        onchange="this.form.submit()"
                        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer"
                    >
                    <span class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                        <i class="fas fa-trash-restore text-gray-500"></i>
                        Show Deleted
                    </span>
                </label>
            </div>
        
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search text-gray-500"></i>
                        Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Name, email, username..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    >
                </div>
                
                <!-- Onboarding Status -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clipboard-check text-gray-500"></i>
                        Onboarding Status
                    </label>
                    <select name="onboarding" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('onboarding') == 'completed' ? 'selected' : '' }}>✓ Completed</option>
                        <option value="pending" {{ request('onboarding') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    </select>
                </div>
                
                <!-- Wearable Type -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-stopwatch text-gray-500"></i>
                        Wearable Device
                    </label>
                    <select name="wearable" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="all">All Devices</option>
                        <option value="fitbit" {{ request('wearable') == 'fitbit' ? 'selected' : '' }}>⌚ Fitbit</option>
                        <option value="huawei" {{ request('wearable') == 'huawei' ? 'selected' : '' }}>⌚ Huawei</option>
                        <option value="apple" {{ request('wearable') == 'apple' ? 'selected' : '' }}>🍎 Apple Watch</option>
                        <option value="samsung" {{ request('wearable') == 'samsung' ? 'selected' : '' }}>⌚ Samsung</option>
                        <option value="none" {{ request('wearable') == 'none' ? 'selected' : '' }}>❌ Not Connected</option>
                    </select>
                </div>
                
                <!-- Apply Button -->
                <div class="flex items-end">
                    <button 
                        type="submit" 
                        class="w-full px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-md flex items-center justify-center gap-2 font-medium"
                    >
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Wearable</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                    <tr class="{{ $patient->trashed() ? 'bg-red-50' : 'hover:bg-gray-50' }} transition-colors">
                        <!-- Patient Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($patient->user->avatar)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($patient->user->avatar) }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-semibold">
                                            {{ substr($patient->full_name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $patient->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ '@' . $patient->user->username }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $patient->user->email }}</div>
                            <div class="text-sm text-gray-500">{{ $patient->phone }}</div>
                        </td>

                        <!-- Age -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $patient->age }} years
                        </td>

                        <!-- Wearable -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($patient->wearable_type !== 'none')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($patient->wearable_type) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    Not Connected
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($patient->trashed())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Deleted
                                </span>
                            @elseif($patient->onboarding_completed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pending
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($patient->trashed())
                                <!-- Restore Button -->
                                <form method="POST" action="{{ route('admin.patients.restore', $patient->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 font-medium mr-3">
                                        Restore
                                    </button>
                                </form>
                                <!-- Permanent Delete -->
                                <form method="POST" action="{{ route('admin.patients.force-delete', $patient->id) }}" class="inline"
                                      onsubmit="return confirm('⚠️ PERMANENTLY DELETE this patient? This CANNOT be undone!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                        Delete Forever
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.patients.show', $patient->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                    <button @click="openEdit({{ $patient->id }})" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors" title="Edit Patient">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.patients.destroy', $patient->id) }}" class="inline" onsubmit="return confirm('Delete this patient? (Soft delete)')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors" title="Delete Patient">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-500 text-lg">No patients found</p>
                            <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or add a new patient</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $patients->links() }}
        </div>
    </div>

    <!-- RIGHT SIDEBAR MODAL - INSIDE Alpine scope -->
    <div 
        x-show="rightSidebarOpen"
        @click.self="closeModal()"
        class="fixed inset-0 bg-black/50 z-40"
        x-cloak
        style="display: none;"
    >
        <aside 
            x-show="rightSidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-96 bg-white shadow-2xl overflow-y-auto"
            @click.stop
        >
<div x-show="rightSidebarOpen" class="h-full flex flex-col bg-white">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-primary to-primary-dark">
        <h2 class="text-xl font-bold text-white font-sora" x-text="editMode ? 'Edit Patient' : 'Add New Patient'"></h2>
        <button @click="closeModal()" class="text-white hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Form Content -->
    <div class="flex-1 overflow-y-auto p-6">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">Validation Errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        <form :action="formAction" method="POST" @submit="handleSubmit">
            @csrf
            <input type="hidden" name="_method" :value="formMethod">

            <!-- Username (only for create, disabled for edit) -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
                <input 
                    type="text" 
                    name="username"
                    x-model="formData.username"
                    :required="!editMode"
                    :disabled="editMode"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent disabled:bg-gray-100"
                    placeholder="john_doe"
                >
                <p x-show="editMode" class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                <input 
                    type="email" 
                    name="email"
                    x-model="formData.email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="john@example.com"
                >
            </div>

            <!-- Password (only for create) -->
            <div class="mb-4" x-show="!editMode">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                <input 
                    type="password" 
                    name="password"
                    x-model="formData.password"
                    :required="!editMode"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="••••••••"
                >
            </div>

            <div class="mb-4" x-show="!editMode">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                <input 
                    type="password" 
                    name="password_confirmation"
                    x-model="formData.password_confirmation"
                    :required="!editMode"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="••••••••"
                >
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input 
                    type="text" 
                    name="full_name"
                    x-model="formData.full_name"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="John Doe"
                >
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                <input 
                    type="date" 
                    name="date_of_birth"
                    x-model="formData.date_of_birth"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                >
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="formData.gender === 'male' ? 'border-primary bg-primary/10' : 'border-gray-300 hover:border-primary'">
                        <input type="radio" name="gender" value="male" x-model="formData.gender" required class="mr-2">
                        <span class="text-sm font-medium">Male</span>
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="formData.gender === 'female' ? 'border-primary bg-primary/10' : 'border-gray-300 hover:border-primary'">
                        <input type="radio" name="gender" value="female" x-model="formData.gender" required class="mr-2">
                        <span class="text-sm font-medium">Female</span>
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-colors"
                           :class="formData.gender === 'other' ? 'border-primary bg-primary/10' : 'border-gray-300 hover:border-primary'">
                        <input type="radio" name="gender" value="other" x-model="formData.gender" required class="mr-2">
                        <span class="text-sm font-medium">Other</span>
                    </label>
                </div>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                <input 
                    type="tel" 
                    name="phone"
                    x-model="formData.phone"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="+62 812 3456 7890"
                >
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                <textarea 
                    name="address"
                    x-model="formData.address"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="Full address..."
                ></textarea>
            </div>

            <!-- Racial Origin -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Racial Origin</label>
                <input 
                    type="text" 
                    name="racial_origin"
                    x-model="formData.racial_origin"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="e.g., Caucasian, Asian"
                >
            </div>

            <!-- Height & Weight -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Height (cm)</label>
                    <input 
                        type="number" 
                        name="height"
                        x-model="formData.height"
                        step="0.1"
                        min="50"
                        max="300"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="170"
                    >
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                    <input 
                        type="number" 
                        name="weight"
                        x-model="formData.weight"
                        step="0.1"
                        min="10"
                        max="500"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="70"
                    >
                </div>
            </div>

            <!-- Blood Type -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Blood Type</label>
                <select name="blood_type" x-model="formData.blood_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Select Blood Type</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3 sticky bottom-0 bg-white pt-4 pb-2 border-t border-gray-200 -mx-6 px-6">
                <button 
                    type="button"
                    @click="closeModal()"
                    class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                    style="min-height: 48px;"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-3 rounded-lg font-semibold transition-colors shadow-lg flex items-center justify-center text-white"
                    style="background: linear-gradient(135deg, #863588 0%, #6B2A6E 100%); min-height: 48px;"
                >
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Saving...' : (editMode ? 'Update Patient' : 'Create Patient')" style="color: white; font-weight: 600;">Create Patient</span>
                </button>
            </div>
        </form>
    </div>
</div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
function patientManagement() {
    return {
        rightSidebarOpen: false,
        editMode: false,
        editId: null,
        formAction: '',
        formMethod: 'POST',
        isSubmitting: false,
        formData: {
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            full_name: '',
            date_of_birth: '',
            gender: '',
            phone: '',
            address: '',
            racial_origin: '',
            height: '',
            weight: '',
            blood_type: ''
        },
        
        init() {
            console.log('Patient Management initialized!');
            
            // Listen for success messages from backend
            @if(session('success'))
                this.showToast('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToast('{{ session('error') }}', 'error');
            @endif
            
            // Re-open modal if there are validation errors
            @if($errors->any())
                console.log('Validation errors detected, reopening modal...');
                this.openCreate();
                // Restore old input values
                this.$nextTick(() => {
                    this.formData = {
                        username: '{{ old('username') }}',
                        email: '{{ old('email') }}',
                        password: '',
                        password_confirmation: '',
                        full_name: '{{ old('full_name') }}',
                        date_of_birth: '{{ old('date_of_birth') }}',
                        gender: '{{ old('gender') }}',
                        phone: '{{ old('phone') }}',
                        address: '{{ old('address') }}',
                        racial_origin: '{{ old('racial_origin') }}',
                        height: '{{ old('height') }}',
                        weight: '{{ old('weight') }}',
                        blood_type: '{{ old('blood_type') }}'
                    };
                });
            @endif
        },
        
        openCreate() {
            console.log('Opening create modal...');
            this.editMode = false;
            this.editId = null;
            this.formAction = '{{ route("admin.patients.store") }}';
            this.formMethod = 'POST';
            this.resetFormData();
            this.rightSidebarOpen = true;
            console.log('Form action:', this.formAction);
            console.log('Form method:', this.formMethod);
        },
        
        async openEdit(id) {
            console.log('Opening edit modal for patient ID:', id);
            this.editMode = true;
            this.editId = id;
            this.formAction = '/admin/patients/' + id;
            this.formMethod = 'PUT';
            this.rightSidebarOpen = true;
            
            console.log('Form action:', this.formAction);
            console.log('Form method:', this.formMethod);
            
            // Fetch patient data via JSON endpoint
            try {
                console.log('Fetching patient data from:', `/admin/patients/${id}/json`);
                const response = await fetch(`/admin/patients/${id}/json`);
                
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`Failed to fetch: ${response.status} ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Patient data received:', data);
                
                // Populate form data with more detailed logging
                this.formData.username = data.username || '';
                this.formData.email = data.email || '';
                this.formData.full_name = data.full_name || '';
                this.formData.date_of_birth = data.date_of_birth || '';
                this.formData.gender = data.gender || '';
                this.formData.phone = data.phone || '';
                this.formData.address = data.address || '';
                this.formData.racial_origin = data.racial_origin || '';
                this.formData.height = data.height || '';
                this.formData.weight = data.weight || '';
                this.formData.blood_type = data.blood_type || '';
                
                console.log('Form data after population:', this.formData);
                
                // Force Alpine to update UI
                this.$nextTick(() => {
                    console.log('Alpine UI updated');
                });
            } catch (error) {
                console.error('Error fetching patient:', error);
                alert('Error: ' + error.message);
                this.showToast('Failed to load patient data', 'error');
                this.closeModal();
            }
        },
        
        resetFormData() {
            this.formData = {
                username: '',
                email: '',
                password: '',
                password_confirmation: '',
                full_name: '',
                date_of_birth: '',
                gender: '',
                phone: '',
                address: '',
                racial_origin: '',
                height: '',
                weight: '',
                blood_type: ''
            };
        },
        
        closeModal() {
            this.rightSidebarOpen = false;
            setTimeout(() => {
                this.resetFormData();
                this.editMode = false;
                this.editId = null;
            }, 300);
        },
        
        handleSubmit(e) {
            console.log('=== FORM SUBMIT ===');
            console.log('Edit Mode:', this.editMode);
            console.log('Form Action:', this.formAction);
            console.log('Form Method:', this.formMethod);
            console.log('Form Data:', this.formData);
            this.isSubmitting = true;
            // Form will submit normally, no AJAX
        },
        
        showToast(message, type = 'success') {
            // Simple toast notification (you can enhance this later)
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }
}
</script>
@endpush
