{{-- resources/views/statistics/partials/_subject_analytics.blade.php --}}
<div class="st-card h-100">
    <div class="st-section-eyebrow">Subjects</div>
    <div class="st-section-title">Subject Analytics</div>

    @if(empty($subjectAnalytics['subjects']))
        <div class="st-empty">
            <div class="st-empty-icon">📚</div>
            <div class="st-empty-text">No subject data yet.<br>Link tasks or sessions to a subject to see analytics.</div>
        </div>
    @else
        {{-- Best & Needs-focus highlight --}}
        <div class="d-flex gap-2 mb-3 flex-wrap">
            @if($subjectAnalytics['strongest'])
                <div class="st-subject-highlight best">
                    <div class="st-subject-highlight-tag">Strongest</div>
                    <div class="st-subject-highlight-name">{{ $subjectAnalytics['strongest']['name'] }}</div>
                    <div class="st-subject-highlight-rate">{{ $subjectAnalytics['strongest']['completion_rate'] }}% done</div>
                </div>
            @endif
            @if($subjectAnalytics['weakest'] && $subjectAnalytics['weakest']['name'] !== ($subjectAnalytics['strongest']['name'] ?? ''))
                <div class="st-subject-highlight needs">
                    <div class="st-subject-highlight-tag">Needs Focus</div>
                    <div class="st-subject-highlight-name">{{ $subjectAnalytics['weakest']['name'] }}</div>
                    <div class="st-subject-highlight-rate">{{ $subjectAnalytics['weakest']['completion_rate'] }}% done</div>
                </div>
            @endif
        </div>

        {{-- Subject table --}}
        <div style="overflow-x:auto; margin-bottom:1.25rem">
            <table class="st-subject-table">
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
                                    <span class="st-badge-best ms-1">Best</span>
                                @elseif($subject['completion_rate'] < 30)
                                    <span class="st-badge-low ms-1">Low</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <div class="st-rate-bar">
                                        <div class="st-rate-fill" style="width:{{ $subject['completion_rate'] }}%"></div>
                                    </div>
                                    <span>{{ $subject['completion_rate'] }}%</span>
                                    <span style="font-size:.7rem;color:var(--st-muted-light)">({{ $subject['completed_tasks'] }}/{{ $subject['total_tasks'] }})</span>
                                </div>
                            </td>
                            <td>
                                {{ $subject['study_minutes'] > 0 ? number_format($subject['study_minutes'] / 60, 1).'h' : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Completion bar chart --}}
        <div class="st-chart-wrap" style="height:160px">
            <canvas id="subjectCompletionChart"></canvas>
        </div>
    @endif
</div>
