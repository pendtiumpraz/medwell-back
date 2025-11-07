@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Audit Logs</h1>
    
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <div class="text-6xl mb-4">📋</div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Coming Soon</h2>
        <p class="text-gray-600">Audit logs page is under development</p>
        
        <div class="mt-8">
            <p class="text-sm text-gray-500">You can check activity logs from Spatie ActivityLog package:</p>
            <code class="bg-gray-100 px-4 py-2 rounded mt-2 inline-block">
                Activity::all()
            </code>
        </div>
    </div>
</div>
@endsection
