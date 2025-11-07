@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-sora">Create Account</h2>
    <p class="text-gray-600 mb-6">Join Medwell health platform</p>
    
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <!-- Username -->
        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
            <input 
                type="text" 
                name="username" 
                id="username" 
                value="{{ old('username') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="johndoe"
            >
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="your.email@example.com"
            >
        </div>
        
        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="••••••••"
            >
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="••••••••"
            >
        </div>
        
        <!-- Terms & Conditions -->
        <div class="mb-6">
            <label class="flex items-start">
                <input 
                    type="checkbox" 
                    name="terms" 
                    required
                    class="w-4 h-4 mt-1 rounded border-gray-300 text-primary focus:ring-primary"
                >
                <span class="ml-2 text-sm text-gray-600">
                    I agree to the <a href="#" class="text-primary hover:text-primary-dark font-medium">Terms of Service</a> and <a href="#" class="text-primary hover:text-primary-dark font-medium">Privacy Policy</a>
                </span>
            </label>
        </div>
        
        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full py-3 px-4 rounded-lg text-white font-semibold text-base transition-all duration-200 shadow-lg hover:shadow-xl"
            style="background: linear-gradient(135deg, #863588 0%, #6B2A6E 100%);"
        >
            Create Account
        </button>
    </form>
    
    <!-- Login Link -->
    <div class="text-center mt-6">
        <span class="text-sm text-gray-600">Already have an account?</span>
        <a href="{{ route('login') }}" class="text-sm text-primary hover:text-primary-dark font-semibold ml-1">
            Sign In
        </a>
    </div>
</div>
@endsection
