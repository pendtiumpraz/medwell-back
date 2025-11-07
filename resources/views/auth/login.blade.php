@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-2 font-sora">Welcome Back</h2>
    <p class="text-gray-600 mb-6">Sign in to continue to Medwell</p>
    
    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    placeholder="your.email@example.com"
                >
            </div>
        </div>
        
        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    placeholder="••••••••"
                >
            </div>
        </div>
        
        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    name="remember" 
                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                >
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-primary-dark font-medium">
                Forgot password?
            </a>
        </div>
        
        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full py-3 px-4 rounded-lg text-white font-semibold text-base transition-all duration-200 shadow-lg hover:shadow-xl"
            style="background: linear-gradient(135deg, #863588 0%, #6B2A6E 100%);"
        >
            Sign In
        </button>
    </form>
    
    <!-- Register Link -->
    <div class="text-center mt-6">
        <span class="text-sm text-gray-600">Don't have an account?</span>
        <a href="{{ route('register') }}" class="text-sm text-primary hover:text-primary-dark font-semibold ml-1">
            Create Account
        </a>
    </div>
</div>
@endsection
