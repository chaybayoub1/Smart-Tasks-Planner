{{--
    resources/views/dashboard/partials/_subject_distribution.blade.php

    Purpose  : Subject distribution analytics widget — donut chart +
               per-subject percentage bars. Shows empty state when no
               subjects have been assigned.
    Included : dashboard/partials/_analytics_row.blade.php
    Data     : $subjectDistribution (array) — keys: labels[], data[],
               colors[], percentages[], top_subject (string), total (int).
               Chart.js canvas id: 'subjectDonutChart' (initialised in
               dashboard/partials/_dashboard_scripts.blade.php).
--}}
<div class="subject-dist-widget">
    <div class="subject-dist-header">
        <span class="subject-dist-title">
            <i class="bi bi-book-fill"></i> Subject Distribution
        </span>
        @if(!empty($subjectDistribution['top_subject']))
            <span class="top-subject-badge" title="Most active subject">
                🏆 {{ $subjectDistribution['top_subject'] }}
            </span>
        @endif
    </div>

    <div class="subject-dist-body">
        @if(empty($subjectDistribution['labels']))
            {{-- Empty state --}}
            <div class="subject-empty">
                <i class="bi bi-journals"></i>
                <p>No subject analytics yet.<br>
                   <a href="{{ route('tasks.index') }}" style="color:var(--db-indigo);font-size:.78rem;">Assign subjects to your tasks</a>
                </p>
            </div>
        @else
            {{-- Donut + legend row --}}
            <div class="subject-dist-inner">
                <div class="subject-dist-canvas-wrap">
                    <canvas id="subjectDonutChart"></canvas>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:7px;">
                    @foreach($subjectDistribution['labels'] as $i => $label)
                    <div class="subject-row">
                        <span class="subject-color-dot" style="background:{{ $subjectDistribution['colors'][$i] }}"></span>
                        <span class="subject-name" title="{{ $label }}">{{ $label }}</span>
                        <div class="subject-pct-bar-wrap">
                            <div class="subject-pct-bar-fill"
                                 style="width:{{ $subjectDistribution['percentages'][$i] }}%;
                                        background:{{ $subjectDistribution['colors'][$i] }};"></div>
                        </div>
                        <span class="subject-pct-label">{{ $subjectDistribution['percentages'][$i] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Total task count footnote --}}
            <p style="margin:0; font-size:.7rem; color:var(--db-muted); text-align:right;">
                {{ $subjectDistribution['total'] }} task{{ $subjectDistribution['total'] !== 1 ? 's' : '' }}
                across {{ count($subjectDistribution['labels']) }} subject{{ count($subjectDistribution['labels']) !== 1 ? 's' : '' }}
            </p>
        @endif
    </div>
</div>
