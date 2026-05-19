{{-- resources/views/statistics/partials/_subject_analytics.blade.php --}}
<div class="stats-card">

    <div class="stats-section-label">Subjects</div>
    <div class="stats-section-title">Subject Analytics</div>

    @if(empty($subjectAnalytics['subjects']))
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <div class="empty-state-text">No subject data yet. Link tasks or sessions to a subject to see analytics.</div>
        </div>
    @else

        {{-- Best & Worst --}}
        <div style="display:flex;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap">
            @if($subjectAnalytics['strongest'])
                <div style="flex:1;min-width:120px;background:var(--positive-soft);border:1px solid rgba(16,185,129,0.2);border-radius:8px;padding:0.75rem 1rem">
                    <div style="font-size:0.68rem;color:var(--positive);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px">Strongest</div>
                    <div style="font-weight:600;font-size:0.9rem;color:var(--text-primary)">{{ $subjectAnalytics['strongest']['name'] }}</div>
                    <div style="font-size:0.78rem;color:var(--positive)">{{ $subjectAnalytics['strongest']['completion_rate'] }}% done</div>
                </div>
            @endif
            @if($subjectAnalytics['weakest'] && $subjectAnalytics['weakest']['name'] !== ($subjectAnalytics['strongest']['name'] ?? ''))
                <div style="flex:1;min-width:120px;background:var(--warning-soft);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:0.75rem 1rem">
                    <div style="font-size:0.68rem;color:var(--warning);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px">Needs Focus</div>
                    <div style="font-weight:600;font-size:0.9rem;color:var(--text-primary)">{{ $subjectAnalytics['weakest']['name'] }}</div>
                    <div style="font-size:0.78rem;color:var(--warning)">{{ $subjectAnalytics['weakest']['completion_rate'] }}% done</div>
                </div>
            @endif
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto">
            <table class="subject-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Completion</th>
                        <th>Study</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjectAnalytics['subjects'] as $subject)
                        <tr>
                            <td>
                                {{ $subject['name'] }}
                                @if($subject['name'] === ($subjectAnalytics['strongest']['name'] ?? ''))
                                    <span class="badge-strong">Best</span>
                                @elseif($subject['completion_rate'] < 30)
                                    <span class="badge-weak">Low</span>
                                @endif
                            </td>
                            <td>
                                <span class="rate-bar-wrap">
                                    <span class="rate-bar-fill" style="width:{{ $subject['completion_rate'] }}%"></span>
                                </span>
                                {{ $subject['completion_rate'] }}%
                                <span style="font-size:0.72rem;color:var(--text-muted)">({{ $subject['completed_tasks'] }}/{{ $subject['total_tasks'] }})</span>
                            </td>
                            <td>
                                {{ $subject['study_minutes'] > 0 ? number_format($subject['study_minutes'] / 60, 1).'h' : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Chart --}}
        <div style="margin-top:1.25rem">
            <div class="chart-container" style="height:160px">
                <canvas id="subjectCompletionChart"></canvas>
            </div>
        </div>

    @endif

</div>
