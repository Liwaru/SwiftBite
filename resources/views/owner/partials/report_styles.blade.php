<style>
    @page {
        size: auto;
        margin: 0;
    }

    :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; --brown-dark: #27140d; --brown: #5a321f; --brown-light: #9a6239; --cream: #fff6e8; }
    body { margin: 0; background: #ffffff; color: #2b1c15; }
    .app-shell .content-with-sidebar { background: #ffffff !important; }
    main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
    h1, h2, p { margin: 0; }
    .hero-card, .stat-card, .panel { border-radius: 8px; box-shadow: 0 16px 38px rgba(39, 20, 13, .13); }
    .hero-card { margin-bottom: 16px; padding: 22px; background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; }
    .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
    .hero-title { font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
    .hero-subtitle { max-width: 760px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
    .stats { display: grid; grid-template-columns: repeat(4, minmax(160px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .stat-card, .panel { background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98)); border: 1px solid rgba(255, 246, 232, .22); color: var(--cream); }
    .stat-card { display: grid; gap: 7px; padding: 16px; }
    .stat-card span, .muted { color: rgba(255, 246, 232, .76); }
    .stat-card span { font-size: 13px; font-weight: 800; }
    .stat-card strong { font-size: 28px; line-height: 1.1; overflow-wrap: anywhere; }
    .report-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; align-items: start; }
    .report-grid.single { grid-template-columns: 1fr; }
    .panel { padding: 18px; }
    .panel h2 { margin-bottom: 14px; font-size: 22px; }
    .filter-panel { margin-bottom: 18px; }
    .filter-form { display: grid; grid-template-columns: 180px 1fr 1fr auto auto; gap: 10px; align-items: end; }
    .filter-field { display: grid; gap: 6px; }
    .filter-field label { color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
    .filter-field input, .filter-field select {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d9b48b;
        border-radius: 8px;
        background: #fffdfa;
        color: #2b1c15;
        padding: 11px 12px;
        font: inherit;
    }
    .filter-btn, .export-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        border: 1px solid #fff6e8;
        border-radius: 8px;
        background: #fff6e8;
        color: var(--brown-dark);
        padding: 10px 13px;
        font: inherit;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }
    .export-btn { background: transparent; color: #fff8ed; border-color: rgba(255, 246, 232, .58); }
    .export-btn:hover, .filter-btn:hover { background: #fffdfa; color: var(--brown-dark); }
    .panel-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
    .panel-head h2 { margin-bottom: 0; }
    .period-note { color: rgba(255, 246, 232, .7); font-size: 13px; font-weight: 800; line-height: 1.45; }
    .chart-panel { margin-bottom: 16px; }
    .chart-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 12px;
    }
    .chart-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 7px;
    }
    .chart-title { font-size: 22px; font-weight: 900; }
    .chart-subtitle { margin-top: 5px; color: rgba(255, 246, 232, .74); font-size: 13px; font-weight: 800; }
    .chart-select {
        width: 150px;
        min-width: 150px;
        min-height: 44px;
        border: 1px solid #d9b48b;
        border-radius: 8px;
        background: #fffdfa;
        color: #2b1c15;
        padding: 10px 12px;
        font: inherit;
        font-weight: 900;
    }
    .chart-action-btn {
        width: 108px;
        min-height: 44px;
        box-sizing: border-box;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 246, 232, .58);
        border-radius: 8px;
        background: transparent;
        color: #fff8ed;
        padding: 9px 12px;
        font: inherit;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }
    .chart-action-btn.print-btn {
        width: 72px;
    }
    .chart-action-btn:hover {
        background: #fff6e8;
        color: var(--brown-dark);
    }
    .line-chart {
        position: relative;
        height: 220px;
        border: 1px solid rgba(255, 246, 232, .16);
        border-radius: 8px;
        background:
            repeating-linear-gradient(to top, rgba(255, 246, 232, .11) 0 1px, transparent 1px 44px),
            rgba(255, 246, 232, .06);
        overflow: hidden;
    }
    .chart-empty {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        color: rgba(255, 246, 232, .76);
        font-weight: 900;
    }
    .line-chart svg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
    .line-chart polyline,
    .line-chart circle {
        vector-effect: non-scaling-stroke;
    }
    .line-chart-labels {
        display: grid;
        gap: 4px;
        margin-top: 8px;
        color: rgba(255, 246, 232, .72);
        font-size: 11px;
        font-weight: 900;
    }
    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
        color: rgba(255, 246, 232, .78);
        font-size: 12px;
        font-weight: 900;
    }
    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #fff6e8;
    }
    .legend-dot.expense { background: #f2bd84; }
    .summary-table { width: 100%; border-collapse: collapse; min-width: 680px; }
    .table-wrap { overflow-x: auto; border: 1px solid rgba(255, 246, 232, .18); border-radius: 8px; }
    .summary-table th, .summary-table td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
    .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
    .summary-table td:last-child { text-align: right; font-weight: 900; }
    .list-stack { display: grid; gap: 10px; }
    .row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; padding-top: 10px; border-top: 1px solid rgba(255, 246, 232, .16); font-weight: 900; }
    .row:first-child { border-top: 0; padding-top: 0; }
    .bar-track { height: 8px; border-radius: 999px; background: rgba(255, 246, 232, .14); overflow: hidden; margin-top: 7px; }
    .bar-fill { height: 100%; border-radius: inherit; background: #fff6e8; }
    .status { display: inline-flex; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 900; }
    .status.safe { background: #e6ffd9; color: #2c642b; }
    .status.low { background: #fff0b8; color: #755000; }
    .status.empty { background: #ffe2dc; color: #7b2418; }
    .status.neutral { background: #fff6e8; color: var(--brown-dark); }
    .pagination-wrap { display: flex; justify-content: flex-end; margin-top: 14px; }
    .pagination { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
    .page-link, .page-current {
        min-width: 38px;
        box-sizing: border-box;
        border: 1px solid rgba(255, 246, 232, .26);
        border-radius: 7px;
        padding: 9px 11px;
        color: #fff8ed;
        text-align: center;
        text-decoration: none;
        font-weight: 900;
    }
    .page-current { background: #fff6e8; color: var(--brown-dark); }
    .page-disabled { opacity: .45; }
    .empty-state { color: rgba(255, 246, 232, .76); font-weight: 800; line-height: 1.5; }
    @media (max-width: 1120px) { .filter-form { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 980px) { .stats, .report-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media print {
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
        .sidebar,
        .filter-panel,
        .chart-actions,
        .sidebar-footer {
            display: none !important;
        }
        .content-with-sidebar {
            margin-left: 0 !important;
        }
        main {
            padding: 12mm !important;
        }
    }
    @media (max-width: 760px) { main { padding: 24px 16px 44px; } .stats, .report-grid { grid-template-columns: 1fr; } .chart-head { flex-direction: column; } .chart-actions, .chart-select { width: 100%; } .chart-action-btn, .chart-action-btn.print-btn { flex: 1; width: auto; } }
</style>
