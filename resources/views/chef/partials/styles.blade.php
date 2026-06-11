<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; --brown-dark: #27140d; --brown: #5a321f; --brown-light: #9a6239; --cream: #fff6e8; }
    body { margin: 0; background: #ffffff; color: #2b1c15; }
    .chef-shell { min-height: 100vh; background: #ffffff; }
    .topbar {
        position: sticky;
        top: 0;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        padding: 14px 16px;
        background: linear-gradient(135deg, var(--brown-light), var(--brown) 48%, var(--brown-dark));
        color: var(--cream);
        box-shadow: 0 8px 26px rgba(39, 20, 13, .18);
    }
    .brand { display: flex; align-items: center; gap: 10px; min-width: 0; color: inherit; text-decoration: none; }
    .logo { width: 48px; height: 48px; display: grid; place-content: center; border-radius: 8px; background: var(--cream); overflow: hidden; }
    .logo img { width: 100%; height: 100%; display: block; border-radius: inherit; object-fit: cover; }
    .brand strong, .brand span { display: block; white-space: nowrap; }
    .brand span { color: rgba(255, 246, 232, .75); font-size: 12px; font-weight: 800; }
    .logout { margin: 0; }
    .logout button { border: 1px solid rgba(255, 246, 232, .32); border-radius: 7px; background: rgba(255, 246, 232, .12); color: var(--cream); padding: 8px 10px; font: inherit; font-size: 13px; font-weight: 900; cursor: pointer; }
    main { width: min(100%, 1080px); margin: 0 auto; box-sizing: border-box; padding: 24px 16px 44px; }
    h1, h2, p { margin: 0; }
    .hero-card, .stat-card, .panel { border-radius: 8px; box-shadow: 0 16px 38px rgba(39, 20, 13, .13); }
    .hero-card { margin-bottom: 16px; padding: 22px; background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; }
    .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
    .hero-title { font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
    .hero-subtitle { max-width: 760px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
    .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 18px; }
    .stat-card, .panel { background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98)); border: 1px solid rgba(255, 246, 232, .22); color: var(--cream); }
    .stat-card { display: grid; gap: 7px; min-width: 0; padding: 14px; }
    .stat-card span, .muted { color: rgba(255, 246, 232, .76); }
    .stat-card span { font-size: 12px; font-weight: 800; line-height: 1.25; overflow-wrap: anywhere; }
    .stat-card strong { font-size: 26px; line-height: 1.1; }
    .grid { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr); gap: 16px; align-items: start; }
    .panel { padding: 18px; }
    .panel h2 { margin-bottom: 14px; font-size: 22px; }
    .table-wrap { overflow-x: auto; border: 1px solid rgba(255, 246, 232, .18); border-radius: 8px; }
    table { width: 100%; border-collapse: collapse; min-width: 720px; }
    th, td { padding: 13px 14px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
    th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
    td:last-child { text-align: right; }
    .list-stack { display: grid; gap: 10px; }
    .order-card, .ingredient-row { border-top: 1px solid rgba(255, 246, 232, .16); padding-top: 10px; font-weight: 900; }
    .order-card:first-child, .ingredient-row:first-child { border-top: 0; padding-top: 0; }
    .order-meta, .small { margin-top: 5px; color: rgba(255, 246, 232, .7); font-size: 13px; font-weight: 800; }
    .flow-track { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 5px; margin-top: 10px; }
    .flow-step { min-width: 0; border: 1px solid rgba(255, 246, 232, .18); border-radius: 7px; padding: 8px 4px; background: rgba(255, 246, 232, .08); color: rgba(255, 246, 232, .62); text-align: center; font-size: 11px; font-weight: 900; }
    .flow-step.done { background: rgba(237, 245, 232, .16); color: #dffbd8; border-color: rgba(197, 221, 183, .42); }
    .flow-step.current { background: var(--cream); color: var(--brown-dark); border-color: var(--cream); }
    .table-flow { min-width: 260px; margin-top: 0; }
    .ready-form { margin-top: 10px; }
    .ready-form button { width: 100%; }
    .status { display: inline-flex; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 900; }
    .status.safe { background: #e6ffd9; color: #2c642b; }
    .status.low { background: #fff0b8; color: #755000; }
    .status.empty { background: #ffe2dc; color: #7b2418; }
    .action-form { display: grid; grid-template-columns: 110px minmax(150px, 1fr) auto; gap: 8px; align-items: center; }
    input { width: 100%; box-sizing: border-box; border: 1px solid #d9b48b; border-radius: 8px; background: #fffdfa; color: #2b1c15; padding: 10px 11px; font: inherit; }
    button, .link-btn { border-radius: 8px; border: 1px solid #fff6e8; background: #fff6e8; color: var(--brown-dark); padding: 10px 12px; font: inherit; font-weight: 900; text-decoration: none; cursor: pointer; }
    .success-banner, .error-banner { margin-bottom: 14px; border-radius: 8px; padding: 12px 14px; font-weight: 900; }
    .success-banner { background: #e6ffd9; color: #2c642b; border: 1px solid #9ad28e; }
    .error-banner { background: #ffe8dd; color: #7a2414; border: 1px solid #e5a08d; }
    .empty-state { color: rgba(255, 246, 232, .76); font-weight: 800; line-height: 1.5; }
    .attendance-panel { margin-bottom: 18px; }
    .attendance-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-end; margin-bottom: 12px; }
    .attendance-head h2 { margin: 0 0 6px; font-size: 20px; }
    .attendance-table { width: 100%; min-width: 0; margin-bottom: 0; table-layout: fixed; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
    .attendance-table td { width: 50%; border-top: 0; padding: 11px 12px; vertical-align: middle; }
    .attendance-table td:last-child { text-align: right; }
    .attendance-status { display: inline-flex; justify-content: center; width: fit-content; max-width: 100%; border-radius: 6px; padding: 4px 10px; color: #fff; font-size: 13px; font-weight: 900; text-align: center; white-space: normal; }
    .attendance-status.absent { background: #ff4d4f; }
    .attendance-status.in { background: #2f54eb; }
    .attendance-status.out { background: #52c41a; }
    .attendance-status-note { display: block; margin-top: 7px; color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
    .attendance-action { text-align: right; }
    .attendance-button { width: 100%; max-width: 180px; border: 0; border-radius: 6px; padding: 8px 12px; font: inherit; font-size: 13px; font-weight: 900; cursor: pointer; }
    .attendance-button:disabled { cursor: not-allowed; opacity: .58; }
    .attendance-button.check-in { background: var(--cream); color: var(--brown-dark); }
    .attendance-button.check-out { background: #fa8c16; color: #fff; }
    .attendance-button.waiting { background: rgba(255, 246, 232, .2); color: rgba(255, 246, 232, .82); border: 1px solid rgba(255, 246, 232, .26); }
    .attendance-finished { color: #52c41a; font-weight: 900; font-size: 13px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; }
    @media (max-width: 980px) { .grid { grid-template-columns: minmax(0, 1fr) minmax(220px, .85fr); gap: 10px; } .action-form { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }
    @media (max-width: 480px) {
        .topbar { padding: 12px; }
        main { padding-inline: 8px; }
        .stats { gap: 6px; }
        .stat-card { min-height: 72px; padding: 9px 6px; }
        .stat-card span { font-size: 10px; line-height: 1.15; }
        .stat-card strong { font-size: 22px; }
        .attendance-table td { padding: 10px 12px; }
        .attendance-status { justify-content: flex-start; }
        .attendance-button { max-width: 132px; padding-inline: 10px; }
    }
</style>
