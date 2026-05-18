{{--
    resources/views/dashboard/partials/_dashboard_styles.blade.php

    Purpose  : All dashboard-specific CSS, extracted from index.blade.php.
    Included : Inside @push('styles') in dashboard/index.blade.php.
    Data     : None — pure CSS, no PHP variables needed.
--}}
<style>
/* ═══════════════════════════════════════════════════════════════
   DASHBOARD — Smart Tasks Planner
   Aesthetic: refined dark-accent SaaS, geometric precision
═══════════════════════════════════════════════════════════════ */

/* ── Tokens ───────────────────────────────────────────────── */
:root {
    --db-indigo:   #6366f1;
    --db-violet:   #8b5cf6;
    --db-emerald:  #10b981;
    --db-amber:    #f59e0b;
    --db-sky:      #0ea5e9;
    --db-rose:     #f43f5e;
    --db-surface:  #ffffff;
    --db-surface2: #f8f8fc;
    --db-border:   #e8e8f0;
    --db-text:     #1a1a2e;
    --db-muted:    #8888aa;
    --db-radius:   14px;
    --db-shadow:   0 2px 12px rgba(99,102,241,.08), 0 1px 3px rgba(0,0,0,.04);
    --db-shadow-hover: 0 8px 28px rgba(99,102,241,.14), 0 2px 8px rgba(0,0,0,.06);
}

/* ── KPI Cards ────────────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.kpi-card {
    position: relative;
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--db-shadow-hover);
}

.kpi-accent {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--db-radius) var(--db-radius) 0 0;
}

.kpi-inner {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 20px;
}

.kpi-icon-wrap {
    flex-shrink: 0;
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}

.kpi-body { flex: 1; min-width: 0; }
.kpi-label {
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--db-muted);
    margin: 0 0 4px;
}
.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--db-text);
    margin: 0;
    line-height: 1;
    letter-spacing: -.02em;
}
.kpi-sub {
    font-size: .72rem;
    color: var(--db-muted);
    margin: 4px 0 0;
}

.kpi-trend {
    font-size: .7rem;
    font-weight: 700;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    padding: 4px 8px;
    border-radius: 8px;
    white-space: nowrap;
}
.trend-up   { color: var(--db-emerald); background: #10b98112; }
.trend-down { color: var(--db-rose);    background: #f43f5e12; }

/* ── Chart Cards ──────────────────────────────────────────── */
.chart-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    display: flex;
    flex-direction: column;
    transition: box-shadow .18s ease;
}
.chart-card:hover { box-shadow: var(--db-shadow-hover); }

.chart-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px 0;
}
.chart-card-title {
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex;
    align-items: center;
    gap: 8px;
}
.chart-card-title i { color: var(--db-indigo); font-size: 1rem; }

.chart-badge {
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-muted);
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    padding: 3px 9px;
    border-radius: 20px;
}

.chart-card-body {
    padding: 16px 20px 20px;
    flex: 1;
    position: relative;
}

/* ── Section Dividers ─────────────────────────────────────── */
.db-section-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--db-muted);
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.db-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--db-border);
}

/* ── Donut Stat Labels ────────────────────────────────────── */
.donut-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: center;
    padding: 8px 0;
}
.donut-legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .8rem;
}
.donut-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.donut-legend-label { color: var(--db-muted); flex: 1; }
.donut-legend-val   { font-weight: 700; color: var(--db-text); }

/* ── Notification / Task List Cards ──────────────────────── */
.notif-card, .task-list-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
}
.notif-card-header, .task-list-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--db-border);
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--db-text);
}
.notif-card-header i, .task-list-card-header i { color: var(--db-indigo); }

/* ── XP / Level Card ──────────────────────────────────────── */
.xp-card {
    background: linear-gradient(135deg, var(--db-indigo), var(--db-violet));
    border-radius: var(--db-radius);
    padding: 20px;
    color: #fff;
    box-shadow: 0 4px 20px rgba(99,102,241,.3);
}
.xp-bar-track {
    background: rgba(255,255,255,.2);
    border-radius: 99px;
    height: 8px;
    overflow: hidden;
    margin: 10px 0 6px;
}
.xp-bar-fill {
    height: 100%;
    background: #fff;
    border-radius: 99px;
    transition: width .6s ease;
}

/* ── Quick Actions ────────────────────────────────────────── */
.quick-actions-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    padding: 20px;
}
.qa-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid transparent;
    text-decoration: none;
    transition: all .15s ease;
}
.qa-btn-primary {
    background: var(--db-indigo);
    color: #fff;
    border-color: var(--db-indigo);
}
.qa-btn-primary:hover { background: var(--db-violet); border-color: var(--db-violet); color:#fff; }
.qa-btn-outline {
    background: var(--db-surface2);
    color: var(--db-text);
    border-color: var(--db-border);
}
.qa-btn-outline:hover { border-color: var(--db-indigo); color: var(--db-indigo); background:#fff; }

/* ── Badges ───────────────────────────────────────────────── */
.badge-chip {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    padding: 8px 10px;
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    border-radius: 10px;
    transition: transform .15s;
}
.badge-chip:hover { transform: scale(1.08); }
.badge-chip-icon { font-size: 1.5rem; line-height: 1; }
.badge-chip-name {
    font-size: .58rem;
    color: var(--db-muted);
    font-weight: 600;
    letter-spacing: .04em;
    max-width: 56px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}

/* ══════════════════════════════════════════════════════════
   TASK STATUS WIDGET
══════════════════════════════════════════════════════════ */
.status-widget {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
    transition: box-shadow .18s ease;
}
.status-widget:hover { box-shadow: var(--db-shadow-hover); }

.status-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--db-border);
}
.status-widget-title {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex; align-items: center; gap: 7px;
}
.status-widget-title i { color: var(--db-indigo); }
.status-widget-total {
    font-size: .72rem;
    font-weight: 600;
    color: var(--db-muted);
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    padding: 3px 10px;
    border-radius: 20px;
}

.status-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 18px;
    border-bottom: 1px solid var(--db-border);
    transition: background .15s;
}
.status-row:last-child { border-bottom: none; }
.status-row:hover { background: var(--db-surface2); }

.status-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-label {
    font-size: .8rem;
    font-weight: 500;
    color: var(--db-text);
    flex: 0 0 82px;
}

.status-bar-wrap {
    flex: 1;
    background: #f0f0f8;
    border-radius: 99px;
    height: 6px;
    overflow: hidden;
}
.status-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .55s cubic-bezier(.4,0,.2,1);
}

.status-count {
    font-size: .78rem;
    font-weight: 700;
    color: var(--db-text);
    flex: 0 0 22px;
    text-align: right;
}

.status-pct {
    font-size: .7rem;
    font-weight: 600;
    flex: 0 0 38px;
    text-align: right;
    color: var(--db-muted);
}

.status-row.is-overdue { background: #fff5f5; }
.status-row.is-overdue:hover { background: #fee2e2; }
.status-row.is-overdue .status-label { color: var(--db-rose); font-weight: 600; }
.status-row.is-overdue .status-pct   { color: var(--db-rose); }
.overdue-badge {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .04em;
    padding: 2px 7px;
    border-radius: 20px;
    background: #f43f5e18;
    color: var(--db-rose);
    border: 1px solid #f43f5e30;
    white-space: nowrap;
}

/* ══════════════════════════════════════════════════════════
   SUBJECT DISTRIBUTION WIDGET
══════════════════════════════════════════════════════════ */
.subject-dist-widget {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: box-shadow .18s ease;
    flex: 1;
}
.subject-dist-widget:hover { box-shadow: var(--db-shadow-hover); }

.subject-dist-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--db-border);
}
.subject-dist-title {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex; align-items: center; gap: 7px;
}
.subject-dist-title i { color: var(--db-indigo); }

.top-subject-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .03em;
    color: var(--db-amber);
    background: #f59e0b10;
    border: 1px solid #f59e0b30;
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.subject-dist-body {
    padding: 14px 18px 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.subject-dist-inner {
    display: flex;
    align-items: center;
    gap: 16px;
}
.subject-dist-canvas-wrap {
    flex-shrink: 0;
    width: 110px; height: 110px;
    position: relative;
}
.subject-dist-canvas-wrap canvas {
    width: 100% !important;
    height: 100% !important;
}

.subject-row {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: .78rem;
    padding: 3px 0;
}
.subject-color-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.subject-name {
    flex: 1;
    color: var(--db-text);
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 90px;
}
.subject-pct-bar-wrap {
    flex: 1;
    background: #f0f0f8;
    border-radius: 99px;
    height: 5px;
    overflow: hidden;
    min-width: 30px;
}
.subject-pct-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.subject-pct-label {
    font-size: .7rem;
    font-weight: 700;
    color: var(--db-muted);
    flex: 0 0 34px;
    text-align: right;
}

.subject-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 24px 0;
    color: var(--db-muted);
    text-align: center;
}
.subject-empty i { font-size: 2rem; opacity: .4; }
.subject-empty p { font-size: .82rem; margin: 0; line-height: 1.4; }
</style>
