@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 font-sora">Welcome back, {{ auth()->user()->username }}! 👋</h1>
    <p class="text-gray-600 mt-2">Here's what's happening with your health platform today.</p>
</div>

@if(auth()->user()->isAdmin())
    @include('dashboards.admin')
@elseif(auth()->user()->isClinician())
    @include('dashboards.clinician')
@elseif(auth()->user()->isPatient())
    @include('dashboards.patient')
@endif
@endsection
