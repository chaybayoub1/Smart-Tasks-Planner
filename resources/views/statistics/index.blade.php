{{-- resources/views/statistics/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistics — Smart Tasks Planner')

@section('content')

@include('statistics.partials._statistics_styles')

<div class="stats-page">

    {{-- Header --}}
    @include('statistics.partials._statistics_header')

    {{-- KPI Row --}}
    @include('statistics.partials._statistics_kpis')

    {{-- Heatmap --}}
    @include('statistics.partials._heatmap')

    {{-- Weekly Comparison --}}
    @include('statistics.partials._weekly_comparison')

    <div class="stats-grid-2">

        {{-- Subject Analytics --}}
        @include('statistics.partials._subject_analytics')

        {{-- Focus Analytics --}}
        @include('statistics.partials._focus_analytics')

    </div>

    {{-- Trend Charts --}}
    @include('statistics.partials._trend_charts')

    {{-- Insights --}}
    @include('statistics.partials._insights')

</div>

@include('statistics.partials._statistics_scripts')

@endsection
