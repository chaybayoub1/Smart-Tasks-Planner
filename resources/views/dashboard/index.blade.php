{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

{{-- ── Styles ────────────────────────────────────────────────── --}}
@push('styles')
    @include('dashboard.partials._dashboard_styles')
@endpush

{{-- ── Content ───────────────────────────────────────────────── --}}
@section('content')

    {{-- 1. KPI Overview row --}}
    @include('dashboard.partials._kpi_row')

    {{-- 2. Analytics row (Task Status + Subject Distribution + Charts) --}}
    @include('dashboard.partials._analytics_row')

    {{-- 3. Activity row (Upcoming Tasks + Sidebar: Alerts / XP / Badges) --}}
    @include('dashboard.partials._activity_row')

    {{-- 4. Quick Actions bar --}}
    @include('dashboard.partials._quick_actions')

@endsection

{{-- ── Scripts ───────────────────────────────────────────────── --}}
@push('scripts')
    @include('dashboard.partials._dashboard_scripts')
@endpush
