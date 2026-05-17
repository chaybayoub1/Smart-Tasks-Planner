{{--
    resources/views/dashboard/partials/_chart_card.blade.php

    Props (passed via @include):
      $chart = [
        'id'     => string   (canvas element id),
        'title'  => string,
        'badge'  => string   (optional badge text, e.g. "Last 7 days"),
        'icon'   => string   (Bootstrap Icons class without "bi-"),
        'height' => int      (canvas height attribute, default 220),
      ]
--}}
@php
    $badge  = $chart['badge']  ?? null;
    $height = $chart['height'] ?? 220;
@endphp

<div class="chart-card h-100">
    <div class="chart-card-header">
        <div class="chart-card-title">
            <i class="bi bi-{{ $chart['icon'] }}"></i>
            {{ $chart['title'] }}
        </div>
        @if($badge)
            <span class="chart-badge">{{ $badge }}</span>
        @endif
    </div>
    <div class="chart-card-body">
        <canvas id="{{ $chart['id'] }}" height="{{ $height }}"></canvas>
    </div>
</div>
