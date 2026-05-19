{{-- resources/views/statistics/partials/_statistics_header.blade.php --}}
<div class="st-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <div class="st-header-eyebrow">
            <i class="bi bi-graph-up-arrow"></i>
            Analytics Center
        </div>
        <h1 class="st-header-title mb-0">Statistics</h1>
        <p class="st-header-sub mt-1">Deep productivity intelligence for <strong style="color:rgba(255,255,255,.9)">{{ Auth::user()->name }}</strong></p>
    </div>
    <a href="{{ route('dashboard') }}" class="st-header-back">
        <i class="bi bi-grid-1x2-fill"></i>
        Dashboard
    </a>
</div>
