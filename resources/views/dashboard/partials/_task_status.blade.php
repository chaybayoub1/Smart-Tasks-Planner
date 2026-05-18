{{--
    resources/views/dashboard/partials/_task_status.blade.php

    Purpose  : Compact status breakdown widget — four metric rows
               (Completed / In Progress / Pending / Overdue) with
               mini progress bars.
    Included : dashboard/partials/_analytics_row.blade.php
    Data     : $taskStats (array) — keys: total, completed, in_progress,
               pending, overdue, and *_percentage variants.
--}}
<div class="status-widget">
    <div class="status-widget-header">
        <span class="status-widget-title">
            <i class="bi bi-pie-chart-fill"></i> Task Status
        </span>
        <span class="status-widget-total">
            {{ $taskStats['total'] ?? 0 }} total
        </span>
    </div>

    {{-- Completed --}}
    <div class="status-row">
        <span class="status-dot" style="background:#10b981"></span>
        <span class="status-label">Completed</span>
        <div class="status-bar-wrap">
            <div class="status-bar-fill" style="width:{{ $taskStats['completed_percentage'] ?? 0 }}%; background:#10b981;"></div>
        </div>
        <span class="status-count">{{ $taskStats['completed'] ?? 0 }}</span>
        <span class="status-pct">{{ $taskStats['completed_percentage'] ?? 0 }}%</span>
    </div>

    {{-- In Progress --}}
    <div class="status-row">
        <span class="status-dot" style="background:#0ea5e9"></span>
        <span class="status-label">In Progress</span>
        <div class="status-bar-wrap">
            <div class="status-bar-fill" style="width:{{ $taskStats['in_progress_percentage'] ?? 0 }}%; background:#0ea5e9;"></div>
        </div>
        <span class="status-count">{{ $taskStats['in_progress'] ?? 0 }}</span>
        <span class="status-pct">{{ $taskStats['in_progress_percentage'] ?? 0 }}%</span>
    </div>

    {{-- Pending --}}
    <div class="status-row">
        <span class="status-dot" style="background:#f59e0b"></span>
        <span class="status-label">Pending</span>
        <div class="status-bar-wrap">
            <div class="status-bar-fill" style="width:{{ $taskStats['pending_percentage'] ?? 0 }}%; background:#f59e0b;"></div>
        </div>
        <span class="status-count">{{ $taskStats['pending'] ?? 0 }}</span>
        <span class="status-pct">{{ $taskStats['pending_percentage'] ?? 0 }}%</span>
    </div>

    {{-- Overdue — warning tint when non-zero --}}
    <div class="status-row {{ ($taskStats['overdue'] ?? 0) > 0 ? 'is-overdue' : '' }}">
        <span class="status-dot" style="background:#f43f5e"></span>
        <span class="status-label">Overdue</span>
        <div class="status-bar-wrap">
            <div class="status-bar-fill" style="width:{{ $taskStats['overdue_percentage'] ?? 0 }}%; background:#f43f5e;"></div>
        </div>
        <span class="status-count">{{ $taskStats['overdue'] ?? 0 }}</span>
        <span class="status-pct">{{ $taskStats['overdue_percentage'] ?? 0 }}%</span>
    </div>
</div>
