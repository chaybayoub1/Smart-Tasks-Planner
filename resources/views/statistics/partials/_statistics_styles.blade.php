{{-- resources/views/statistics/partials/_statistics_styles.blade.php --}}
<style>
/* ═══════════════════════════════════════════════════════════════════════
   STATISTICS MODULE — Redesigned for SmarTasker (Bootstrap 5 compatible)
   Aesthetic: Refined SaaS · Light surface · Indigo accents · Clean data
═══════════════════════════════════════════════════════════════════════ */

@import url('https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap');

/* ── Tokens (extends app.blade.php :root) ── */
:root {
    --st-indigo:      #6366f1;
    --st-indigo-d:    #4f46e5;
    --st-violet:      #8b5cf6;
    --st-emerald:     #10b981;
    --st-amber:       #f59e0b;
    --st-sky:         #0ea5e9;
    --st-rose:        #f43f5e;
    --st-surface:     #ffffff;
    --st-surface-2:   #f5f5fc;
    --st-border:      #e8e8f4;
    --st-text:        #1a1a2e;
    --st-muted:       #7c7c9c;
    --st-muted-light: #b4b4cc;
    --st-radius:      14px;
    --st-radius-sm:   9px;
    --st-shadow:      0 2px 14px rgba(99,102,241,.07), 0 1px 3px rgba(0,0,0,.04);
    --st-shadow-h:    0 8px 30px rgba(99,102,241,.13), 0 2px 8px rgba(0,0,0,.06);
    --st-shadow-card: 0 1px 8px rgba(0,0,0,.06);
}

/* ── Page wrapper ── */
.st-page {
    font-family: 'DM Sans', sans-serif;
    color: var(--st-text);
}

/* ── Section heading group ── */
.st-section-eyebrow {
    font-family: 'DM Sans', sans-serif;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--st-indigo);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: .3rem;
}
.st-section-eyebrow::before {
    content: '';
    width: 14px; height: 2px;
    background: var(--st-indigo);
    border-radius: 2px;
    display: inline-block;
}
.st-section-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--st-text);
    margin-bottom: 1.2rem;
}

/* ════════════════════════════════════════════
   HEADER
════════════════════════════════════════════ */
.st-header {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    border-radius: var(--st-radius);
    padding: 2rem 2.25rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(99,102,241,.25);
}
.st-header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(165,180,252,.18) 0%, transparent 70%);
    pointer-events: none;
}
.st-header::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 30%;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(99,102,241,.15) 0%, transparent 70%);
    pointer-events: none;
}
.st-header-eyebrow {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(165,180,252,.8);
    margin-bottom: .4rem;
    display: flex;
    align-items: center;
    gap: 6px;
}
.st-header-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.9rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    margin-bottom: .35rem;
}
.st-header-sub {
    font-size: .85rem;
    color: rgba(255,255,255,.6);
    margin: 0;
}
.st-header-back {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .8rem;
    font-weight: 600;
    color: rgba(255,255,255,.65);
    text-decoration: none;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 8px;
    padding: .4rem .85rem;
    transition: all .18s ease;
    backdrop-filter: blur(4px);
}
.st-header-back:hover {
    background: rgba(255,255,255,.2);
    color: #fff;
    border-color: rgba(255,255,255,.35);
}

/* ════════════════════════════════════════════
   KPI CARDS
════════════════════════════════════════════ */
.st-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 1.5rem;
}
@media (max-width: 1100px) { .st-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px)  { .st-kpi-grid { grid-template-columns: 1fr 1fr; } }

.st-kpi {
    background: var(--st-surface);
    border: 1px solid var(--st-border);
    border-radius: var(--st-radius);
    padding: 1.25rem 1.35rem;
    position: relative;
    overflow: hidden;
    box-shadow: var(--st-shadow-card);
    transition: transform .18s ease, box-shadow .18s ease;
}
.st-kpi:hover {
    transform: translateY(-3px);
    box-shadow: var(--st-shadow-h);
}
.st-kpi-top {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--st-radius) var(--st-radius) 0 0;
}
.st-kpi-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    margin-bottom: .75rem;
    flex-shrink: 0;
}
.st-kpi-value {
    font-family: 'Syne', sans-serif;
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--st-text);
    line-height: 1;
    letter-spacing: -.03em;
    margin-bottom: .25rem;
}
.st-kpi-value small {
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0;
}
.st-kpi-label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--st-muted);
    margin-bottom: 0;
}
.st-kpi-sub {
    font-size: .72rem;
    color: var(--st-muted-light);
    margin-top: 3px;
}

/* ── KPI colour variants ── */
.st-kpi.c-indigo .st-kpi-top   { background: var(--st-indigo); }
.st-kpi.c-indigo .st-kpi-icon  { background: rgba(99,102,241,.1); color: var(--st-indigo); }
.st-kpi.c-emerald .st-kpi-top  { background: var(--st-emerald); }
.st-kpi.c-emerald .st-kpi-icon { background: rgba(16,185,129,.1); color: var(--st-emerald); }
.st-kpi.c-amber .st-kpi-top    { background: var(--st-amber); }
.st-kpi.c-amber .st-kpi-icon   { background: rgba(245,158,11,.1); color: var(--st-amber); }
.st-kpi.c-sky .st-kpi-top      { background: var(--st-sky); }
.st-kpi.c-sky .st-kpi-icon     { background: rgba(14,165,233,.1); color: var(--st-sky); }
.st-kpi.c-violet .st-kpi-top   { background: var(--st-violet); }
.st-kpi.c-violet .st-kpi-icon  { background: rgba(139,92,246,.1); color: var(--st-violet); }
.st-kpi.c-rose .st-kpi-top     { background: var(--st-rose); }
.st-kpi.c-rose .st-kpi-icon    { background: rgba(244,63,94,.1); color: var(--st-rose); }

/* ════════════════════════════════════════════
   GENERIC STAT CARD
════════════════════════════════════════════ */
.st-card {
    background: var(--st-surface);
    border: 1px solid var(--st-border);
    border-radius: var(--st-radius);
    box-shadow: var(--st-shadow-card);
    padding: 1.5rem 1.6rem;
    transition: box-shadow .18s ease;
    height: 100%;
}
.st-card:hover {
    box-shadow: var(--st-shadow-h);
}
.st-card-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}

/* ════════════════════════════════════════════
   HEATMAP
════════════════════════════════════════════ */
.st-heatmap-scroll {
    overflow-x: auto;
    padding-bottom: 4px;
}
.st-heatmap-grid {
    display: flex;
    gap: 3px;
    min-width: max-content;
}
.st-heatmap-week {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.st-heatmap-cell {
    width: 13px; height: 13px;
    border-radius: 3px;
    cursor: pointer;
    transition: transform .12s, box-shadow .12s;
    position: relative;
}
.st-heatmap-cell:hover {
    transform: scale(1.45);
    z-index: 5;
}
.st-heat-0 { background: #f0f0f8; }
.st-heat-1 { background: rgba(99,102,241,.22); }
.st-heat-2 { background: rgba(99,102,241,.48); }
.st-heat-3 { background: rgba(99,102,241,.74); }
.st-heat-4 { background: #6366f1; box-shadow: 0 0 6px rgba(99,102,241,.4); }
.st-heat-future { background: transparent; border: 1px solid #e8e8f4; }

.st-heatmap-legend {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: .65rem;
    font-size: .7rem;
    color: var(--st-muted);
}
.st-heatmap-legend-cell {
    width: 11px; height: 11px;
    border-radius: 2px;
}

/* ════════════════════════════════════════════
   WEEKLY COMPARISON
════════════════════════════════════════════ */
.st-compare-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
@media (max-width: 900px) { .st-compare-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 480px) { .st-compare-grid { grid-template-columns: 1fr; } }

.st-compare-item {
    background: var(--st-surface-2);
    border: 1px solid var(--st-border);
    border-radius: var(--st-radius-sm);
    padding: 1.1rem 1.2rem;
    transition: border-color .18s;
}
.st-compare-item:hover { border-color: var(--st-indigo); }

.st-compare-metric {
    font-size: .72rem;
    font-weight: 600;
    color: var(--st-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: .55rem;
    display: flex;
    align-items: center;
    gap: 6px;
}
.st-compare-val {
    font-family: 'Syne', sans-serif;
    font-size: 1.65rem;
    font-weight: 800;
    color: var(--st-text);
    line-height: 1;
    letter-spacing: -.03em;
}
.st-compare-unit {
    font-size: .8rem;
    font-weight: 500;
    color: var(--st-muted);
    letter-spacing: 0;
    margin-left: 2px;
}
.st-compare-prev {
    font-size: .75rem;
    color: var(--st-muted-light);
    margin-top: .3rem;
}
.st-delta {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: .72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    margin-top: .4rem;
}
.st-delta.pos { background: rgba(16,185,129,.1);  color: var(--st-emerald); }
.st-delta.neg { background: rgba(244,63,94,.1);   color: var(--st-rose); }
.st-delta.neu { background: rgba(99,102,241,.1);  color: var(--st-indigo); }

/* ════════════════════════════════════════════
   CHART CONTAINERS
════════════════════════════════════════════ */
.st-chart-wrap {
    position: relative;
    width: 100%;
}
.st-chart-wrap canvas { max-width: 100%; }

/* ════════════════════════════════════════════
   SUBJECT TABLE
════════════════════════════════════════════ */
.st-subject-table {
    width: 100%;
    border-collapse: collapse;
}
.st-subject-table th {
    text-align: left;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--st-muted);
    padding: 0 0 .6rem;
    border-bottom: 2px solid var(--st-border);
}
.st-subject-table td {
    padding: .65rem 0;
    font-size: .84rem;
    color: var(--st-muted);
    border-bottom: 1px solid #f0f0f8;
    vertical-align: middle;
}
.st-subject-table td:first-child {
    color: var(--st-text);
    font-weight: 600;
}
.st-rate-bar {
    width: 72px; height: 5px;
    background: #f0f0f8;
    border-radius: 99px;
    overflow: hidden;
    display: inline-block;
    vertical-align: middle;
    margin-right: 7px;
}
.st-rate-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--st-indigo), var(--st-violet));
    border-radius: 99px;
}
.st-badge-best { background: rgba(16,185,129,.1); color: var(--st-emerald); font-size: .64rem; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
.st-badge-low  { background: rgba(244,63,94,.1);  color: var(--st-rose);    font-size: .64rem; font-weight: 700; padding: 2px 7px; border-radius: 20px; }

/* ════════════════════════════════════════════
   SUBJECT HIGHLIGHT BADGES (strongest/weakest)
════════════════════════════════════════════ */
.st-subject-highlight {
    flex: 1;
    min-width: 120px;
    border-radius: var(--st-radius-sm);
    padding: .85rem 1.1rem;
}
.st-subject-highlight.best {
    background: rgba(16,185,129,.07);
    border: 1px solid rgba(16,185,129,.2);
}
.st-subject-highlight.needs {
    background: rgba(245,158,11,.07);
    border: 1px solid rgba(245,158,11,.2);
}
.st-subject-highlight-tag {
    font-size: .63rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .09em;
    margin-bottom: 3px;
}
.best  .st-subject-highlight-tag { color: var(--st-emerald); }
.needs .st-subject-highlight-tag { color: var(--st-amber); }
.st-subject-highlight-name {
    font-weight: 700;
    font-size: .9rem;
    color: var(--st-text);
}
.st-subject-highlight-rate { font-size: .8rem; margin-top: 1px; }
.best  .st-subject-highlight-rate { color: var(--st-emerald); }
.needs .st-subject-highlight-rate { color: var(--st-amber); }

/* ════════════════════════════════════════════
   FOCUS ANALYTICS
════════════════════════════════════════════ */
.st-focus-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 1.1rem;
}
.st-focus-stat {
    background: var(--st-surface-2);
    border: 1px solid var(--st-border);
    border-radius: var(--st-radius-sm);
    padding: .9rem 1rem;
}
.st-focus-val {
    font-family: 'Syne', sans-serif;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--st-text);
    line-height: 1;
}
.st-focus-lbl {
    font-size: .7rem;
    color: var(--st-muted);
    margin-top: 2px;
}

.st-ring-wrap {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.1rem;
}
.st-ring-lbl {
    font-size: .78rem;
    color: var(--st-muted);
    line-height: 1.4;
}

/* ════════════════════════════════════════════
   TREND CHARTS GRID
════════════════════════════════════════════ */
.st-trend-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .st-trend-grid { grid-template-columns: 1fr; } }

/* ════════════════════════════════════════════
   INSIGHTS
════════════════════════════════════════════ */
.st-insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 12px;
}
.st-insight {
    display: flex;
    gap: .85rem;
    align-items: flex-start;
    padding: 1rem 1.1rem;
    border-radius: var(--st-radius-sm);
    background: var(--st-surface-2);
    border: 1px solid var(--st-border);
    border-left-width: 3px;
    transition: box-shadow .18s, transform .18s;
}
.st-insight:hover {
    box-shadow: var(--st-shadow-h);
    transform: translateY(-2px);
}
.st-insight.positive  { border-left-color: var(--st-emerald); }
.st-insight.warning   { border-left-color: var(--st-amber); }
.st-insight.suggestion { border-left-color: var(--st-indigo); }
.st-insight.pattern   { border-left-color: var(--st-violet); }
.st-insight-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: 1px; }
.st-insight-text { font-size: .84rem; color: var(--st-muted); line-height: 1.5; }

/* ════════════════════════════════════════════
   EMPTY STATE
════════════════════════════════════════════ */
.st-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.75rem 1rem;
    text-align: center;
    color: var(--st-muted-light);
}
.st-empty-icon { font-size: 2.2rem; margin-bottom: .75rem; opacity: .4; }
.st-empty-text { font-size: .84rem; }

/* ════════════════════════════════════════════
   HEATMAP TOOLTIP
════════════════════════════════════════════ */
#st-heatmap-tip {
    position: fixed;
    background: #1e1b4b;
    color: #fff;
    border-radius: 7px;
    padding: 5px 10px;
    font-size: .73rem;
    font-weight: 500;
    pointer-events: none;
    z-index: 1000;
    white-space: nowrap;
    display: none;
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
}

/* ════════════════════════════════════════════
   UTILITY
════════════════════════════════════════════ */
.st-divider-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--st-muted);
    margin-bottom: .9rem;
}
.st-divider-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--st-border);
}

/* Small screen adjustments */
@media (max-width: 576px) {
    .st-header { padding: 1.5rem; }
    .st-header-title { font-size: 1.5rem; }
    .st-kpi-value { font-size: 1.5rem; }
    .st-compare-val { font-size: 1.35rem; }
}
</style>

{{-- Tooltip DOM node --}}
<div id="st-heatmap-tip"></div>
