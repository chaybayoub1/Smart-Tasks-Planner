{{--
    resources/views/dashboard/partials/_kpi_card.blade.php

    Props (passed via @include):
      $kpi = [
        'label'   => string,
        'value'   => string,
        'icon'    => string  (Bootstrap Icons class without "bi-"),
        'color'   => string  (CSS color or gradient stop — used as accent),
        'sub'     => string  (optional sub-label),
        'trend'   => float   (optional delta %, positive = good),
      ]
--}}
@php
    $sub   = $kpi['sub']   ?? null;
    $trend = $kpi['trend'] ?? null;
@endphp

<div class="kpi-card">
    <div class="kpi-accent" style="background: {{ $kpi['color'] }}"></div>
    <div class="kpi-inner">
        <div class="kpi-icon-wrap" style="background: {{ $kpi['color'] }}18; color: {{ $kpi['color'] }}">
            <i class="bi bi-{{ $kpi['icon'] }}"></i>
        </div>
        <div class="kpi-body">
            <p class="kpi-label">{{ $kpi['label'] }}</p>
            <p class="kpi-value">{{ $kpi['value'] }}</p>
            @if($sub)
                <p class="kpi-sub">{{ $sub }}</p>
            @endif
        </div>
        @if($trend !== null)
        <div class="kpi-trend {{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
            <i class="bi bi-arrow-{{ $trend >= 0 ? 'up' : 'down' }}-right-circle-fill"></i>
            {{ abs($trend) }}%
        </div>
        @endif
    </div>
</div>
