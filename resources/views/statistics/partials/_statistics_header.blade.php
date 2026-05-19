{{-- resources/views/statistics/partials/_statistics_header.blade.php --}}
<div class="stats-header">
    <div>
        <div class="stats-section-label">Analytics Center</div>
        <h1 class="stats-header-title">Statistics</h1>
        <p class="stats-header-sub">Deep productivity intelligence for {{ Auth::user()->name }}</p>
    </div>
    <nav class="stats-header-nav">
        <a href="{{ route('dashboard') }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
                <path d="M9 21V12h6v9"/>
            </svg>
            Dashboard
        </a>
    </nav>
</div>
