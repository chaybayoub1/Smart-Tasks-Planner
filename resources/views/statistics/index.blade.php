{{-- resources/views/statistics/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistics — SmarTasker')
@section('page-title', '📊 Statistics')

@push('styles')
    @include('statistics.partials._statistics_styles')
@endpush

@section('content')
<div class="st-page">

    {{-- ── HEADER ──────────────────────────────────────────────────────── --}}
    @include('statistics.partials._statistics_header')

    {{-- ── KPI ROW ──────────────────────────────────────────────────────── --}}
    @include('statistics.partials._statistics_kpis')

    {{-- ── WEEKLY COMPARISON ───────────────────────────────────────────── --}}
    @include('statistics.partials._weekly_comparison')

    {{-- ── HEATMAP ──────────────────────────────────────────────────────── --}}
    @include('statistics.partials._heatmap')

    {{-- ── SUBJECT + FOCUS (2-col) ──────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            @include('statistics.partials._subject_analytics')
        </div>
        <div class="col-12 col-lg-6">
            @include('statistics.partials._focus_analytics')
        </div>
    </div>

    {{-- ── TREND CHARTS + XP CUMULATIVE ───────────────────────────────── --}}
    @include('statistics.partials._trend_charts')

    {{-- ── INSIGHTS ─────────────────────────────────────────────────────── --}}
    @include('statistics.partials._insights')

</div>
@endsection

@push('scripts')
    @include('statistics.partials._statistics_scripts')
@endpush
