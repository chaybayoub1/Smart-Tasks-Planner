{{-- resources/views/statistics/partials/_statistics_styles.blade.php --}}
<style>
/* ============================================================
   STATISTICS MODULE — SaaS Analytics Design System
   Font: DM Sans (body) + Syne (display/headings)
   Palette: deep navy base, electric indigo accent, emerald positive
   ============================================================ */

@import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

:root {
    --bg-base:        #0d0f1a;
    --bg-card:        #13162b;
    --bg-card-hover:  #181c34;
    --bg-border:      rgba(99,102,241,0.15);
    --accent:         #6366f1;
    --accent-glow:    rgba(99,102,241,0.25);
    --accent-soft:    rgba(99,102,241,0.10);
    --positive:       #10b981;
    --positive-soft:  rgba(16,185,129,0.12);
    --warning:        #f59e0b;
    --warning-soft:   rgba(245,158,11,0.12);
    --danger:         #f43f5e;
    --danger-soft:    rgba(244,63,94,0.12);
    --text-primary:   #e8eaf6;
    --text-secondary: #8b92b8;
    --text-muted:     #4c5280;
    --radius:         14px;
    --radius-sm:      8px;
    --shadow:         0 4px 24px rgba(0,0,0,0.4);
    --shadow-accent:  0 8px 32px rgba(99,102,241,0.20);
}

/* ── Base ── */
.stats-page {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg-base);
    color: var(--text-primary);
    min-height: 100vh;
    padding: 2rem 1.5rem 4rem;
    max-width: 1280px;
    margin: 0 auto;
}

/* ── Cards ── */
.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--bg-border);
    border-radius: var(--radius);
    padding: 1.75rem;
    box-shadow: var(--shadow);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.stats-card:hover {
    border-color: rgba(99,102,241,0.35);
    box-shadow: var(--shadow-accent);
}

/* ── Section label ── */
.stats-section-label {
    font-family: 'Syne', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 0.4rem;
}
.stats-section-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1.25rem;
}

/* ── KPI Cards ── */
.stats-kpis {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.75rem;
}
.kpi-card {
    background: var(--bg-card);
    border: 1px solid var(--bg-border);
    border-radius: var(--radius);
    padding: 1.4rem 1.5rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-accent); }
.kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    border-radius: 0 2px 2px 0;
}
.kpi-card.accent::before  { background: var(--accent); }
.kpi-card.positive::before { background: var(--positive); }
.kpi-card.warning::before  { background: var(--warning); }
.kpi-card.danger::before   { background: var(--danger); }

.kpi-icon {
    font-size: 1.3rem;
    margin-bottom: 0.6rem;
    display: block;
}
.kpi-value {
    font-family: 'Syne', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    color: var(--text-primary);
}
.kpi-label {
    font-size: 0.78rem;
    color: var(--text-secondary);
    margin-top: 0.3rem;
}

/* ── Heatmap ── */
.heatmap-wrapper {
    overflow-x: auto;
    margin-bottom: 1.75rem;
}
.heatmap-grid {
    display: flex;
    gap: 3px;
    min-width: max-content;
}
.heatmap-week {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.heatmap-cell {
    width: 13px;
    height: 13px;
    border-radius: 3px;
    cursor: pointer;
    transition: transform 0.15s;
    position: relative;
}
.heatmap-cell:hover { transform: scale(1.4); z-index: 10; }
.heat-0 { background: rgba(99,102,241,0.06); }
.heat-1 { background: rgba(99,102,241,0.25); }
.heat-2 { background: rgba(99,102,241,0.50); }
.heat-3 { background: rgba(99,102,241,0.75); }
.heat-4 { background: #6366f1; box-shadow: 0 0 6px rgba(99,102,241,0.5); }
.heat-future { background: transparent; border: 1px solid rgba(99,102,241,0.08); }

.heatmap-legend {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 0.75rem;
    font-size: 0.72rem;
    color: var(--text-muted);
}
.heatmap-legend-cell {
    width: 11px; height: 11px;
    border-radius: 2px;
}

/* ── Weekly Comparison ── */
.comparison-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.comparison-card {
    background: var(--bg-base);
    border: 1px solid var(--bg-border);
    border-radius: var(--radius-sm);
    padding: 1.2rem 1.4rem;
}
.comparison-metric { font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.5rem; }
.comparison-values { display: flex; align-items: flex-end; gap: 0.5rem; }
.comparison-current {
    font-family: 'Syne', sans-serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}
.comparison-prev {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 2px;
}
.comparison-delta {
    font-size: 0.78rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 20px;
    margin-top: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.delta-pos { background: var(--positive-soft); color: var(--positive); }
.delta-neg { background: var(--danger-soft); color: var(--danger); }
.delta-neu { background: var(--accent-soft); color: var(--accent); }

/* ── 2-col grid ── */
.stats-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 768px) { .stats-grid-2 { grid-template-columns: 1fr; } }

/* ── Subject table ── */
.subject-table { width: 100%; border-collapse: collapse; }
.subject-table th {
    text-align: left;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0 0 0.6rem;
    border-bottom: 1px solid var(--bg-border);
}
.subject-table td {
    padding: 0.7rem 0;
    font-size: 0.85rem;
    border-bottom: 1px solid rgba(99,102,241,0.06);
    color: var(--text-secondary);
}
.subject-table td:first-child { color: var(--text-primary); font-weight: 500; }
.rate-bar-wrap {
    width: 80px;
    height: 5px;
    background: rgba(99,102,241,0.12);
    border-radius: 10px;
    overflow: hidden;
    display: inline-block;
    vertical-align: middle;
    margin-right: 6px;
}
.rate-bar-fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, var(--accent), var(--positive));
}
.badge-strong { background: var(--positive-soft); color: var(--positive); font-size: 0.68rem; padding: 2px 6px; border-radius: 20px; }
.badge-weak   { background: var(--danger-soft);   color: var(--danger);   font-size: 0.68rem; padding: 2px 6px; border-radius: 20px; }

/* ── Chart containers ── */
.chart-container {
    position: relative;
    width: 100%;
}
.chart-container canvas { max-width: 100%; }

/* ── Focus stats grid ── */
.focus-stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.focus-stat {
    background: var(--bg-base);
    border: 1px solid var(--bg-border);
    border-radius: var(--radius-sm);
    padding: 0.9rem 1rem;
}
.focus-stat-value {
    font-family: 'Syne', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}
.focus-stat-label { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }

/* ── Trend charts ── */
.trend-charts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}
@media (max-width: 900px) { .trend-charts-grid { grid-template-columns: 1fr; } }

/* ── Insights ── */
.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1rem;
}
.insight-card {
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
    padding: 1rem 1.2rem;
    border-radius: var(--radius-sm);
    background: var(--bg-base);
    border: 1px solid var(--bg-border);
    transition: border-color 0.2s;
}
.insight-card:hover { border-color: var(--accent); }
.insight-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 1px; }
.insight-text { font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; }
.insight-card.positive { border-left: 3px solid var(--positive); }
.insight-card.warning  { border-left: 3px solid var(--warning); }
.insight-card.suggestion { border-left: 3px solid var(--accent); }
.insight-card.pattern  { border-left: 3px solid #8b5cf6; }

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
}
.empty-state-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }
.empty-state-text { font-size: 0.9rem; }

/* ── Tooltip (heatmap) ── */
#heatmap-tooltip {
    position: fixed;
    background: var(--bg-card);
    border: 1px solid var(--bg-border);
    border-radius: var(--radius-sm);
    padding: 6px 10px;
    font-size: 0.75rem;
    color: var(--text-primary);
    pointer-events: none;
    z-index: 999;
    white-space: nowrap;
    display: none;
    box-shadow: var(--shadow);
}

/* ── Header ── */
.stats-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.stats-header-title {
    font-family: 'Syne', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #e8eaf6 0%, #6366f1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}
.stats-header-sub {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 0.3rem;
}
.stats-header-nav a {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.82rem;
    color: var(--text-secondary);
    text-decoration: none;
    background: var(--bg-card);
    border: 1px solid var(--bg-border);
    border-radius: 8px;
    padding: 0.45rem 0.9rem;
    transition: color 0.2s, border-color 0.2s;
}
.stats-header-nav a:hover { color: var(--accent); border-color: var(--accent); }

/* ── Consistency ring ── */
.ring-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.ring-label {
    font-size: 0.72rem;
    color: var(--text-muted);
    text-align: center;
}
</style>

{{-- Tooltip DOM --}}
<div id="heatmap-tooltip"></div>
