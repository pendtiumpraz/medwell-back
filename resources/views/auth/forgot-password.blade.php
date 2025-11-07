@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 font-sora">
                Forgot Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Feature coming soon. Please contact administrator to reset your password.
            </p>
        </div>

        <div class="mt-8">
            <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
