<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] }}</title>
    <style>
        :root {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --brown-dark: #27140d;
            --brown: #5a321f;
            --brown-mid: #7f4d30;
            --brown-light: #9a6239;
            --cream: #fff6e8;
            --cream-soft: #f4e3cd;
        }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 10px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .page-shell { max-width: 1120px; }
        .hero-card, .summary-card, .filter-card, .table-card, .panel {
            border-radius: 8px;
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .hero-card,
        .summary-card,
        .section-card,
        .menu-carousel,
        .menu-card,
        .action-btn,
        .row-action,
        .count-badge {
            -webkit-user-select: none;
            user-select: none;
        }
        input,
        select,
        textarea,
        .detail-list {
            -webkit-user-select: text;
            user-select: text;
        }
        .hero-card {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
            padding: 22px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
        }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { margin: 0; font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 720px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .hero-action {
            align-self: center;
            min-width: 152px;
            min-height: 46px;
            border-radius: 8px;
            background: #fff6e8;
            color: var(--brown-dark);
            border-color: #fff6e8;
            box-shadow: 0 12px 28px rgba(24, 13, 7, .16);
        }
        .hero-action:hover {
            background: #fffdfa;
            color: var(--brown-dark);
            border-color: #fffdfa;
            transform: translateY(-1px);
        }
        .summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 16px; }
        .summary-card { padding: 18px; background: linear-gradient(135deg, #8f5735, #4a2618); color: #fff8ed; }
        .summary-card.is-accent { background: linear-gradient(135deg, #a96f45, #5a321f); color: #fff8ed; }
        .summary-label { color: inherit; font-size: 13px; font-weight: 900; opacity: .82; }
        .summary-value { margin-top: 8px; font-size: 31px; line-height: 1; font-weight: 900; }
        .summary-note { margin-top: 10px; font-size: 13px; line-height: 1.4; opacity: .76; }
        .count-badge { border-radius: 999px; background: #fff6e8; color: var(--brown-dark); padding: 8px 12px; font-size: 13px; font-weight: 900; white-space: nowrap; }
        .filter-card, .table-card {
            margin-top: 16px;
            padding: 18px;
            background: #fff6e8;
            border: 1px solid #e1ad73;
        }
        .filter-form { display: grid; grid-template-columns: minmax(260px, 1fr) 220px auto; gap: 12px; align-items: end; }
        .filter-field { display: grid; gap: 7px; }
        .filter-field label { color: var(--brown); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .filter-field input, .filter-field select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d9b48b;
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 12px 13px;
            font: inherit;
        }
        .filter-actions { display: flex; gap: 8px; }
        .table-card .filter-form {
            margin-bottom: 16px;
            padding: 14px;
            border: 1px solid rgba(255, 246, 232, .18);
            border-radius: 8px;
            background: rgba(255, 246, 232, .08);
        }
        .table-card .filter-field label {
            color: rgba(255, 248, 237, .86);
        }
        .table-card .filter-field input,
        .table-card .filter-field select {
            border-color: rgba(255, 246, 232, .28);
            background: #fffdfa;
        }
        .filter-btn, .filter-link, .action-btn, .row-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid transparent;
            font: inherit;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .filter-btn, .action-btn.primary { background: var(--brown); color: #fff8ed; }
        .filter-link, .row-action { background: #fffdfa; border-color: #d9b48b; color: var(--brown-dark); }
        .filter-btn:hover { background: var(--brown-dark); }
        .action-btn.primary:hover { background: #fff6e8; color: var(--brown-dark); border-color: #fff6e8; }
        .filter-link:hover, .row-action:hover { background: var(--cream-soft); }
        .table-card { background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; border-color: rgba(255, 246, 232, .22); }
        .table-header { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 16px; }
        .table-title { font-size: 22px; font-weight: 900; }
        .table-subtitle { margin-top: 5px; color: rgba(255, 248, 237, .76); font-size: 14px; font-weight: 700; }
        .table-wrap { overflow-x: auto; border: 1px solid rgba(255, 246, 232, .18); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; min-width: 860px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid rgba(255, 246, 232, .14); vertical-align: middle; }
        th { background: rgba(255, 246, 232, .13); color: rgba(255, 248, 237, .86); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        tr:last-child td { border-bottom: 0; }
        .user-name { font-weight: 900; }
        .user-meta { margin-top: 4px; color: rgba(255, 248, 237, .72); font-size: 13px; font-weight: 700; }
        .muted-text { color: rgba(255, 248, 237, .68); }
        .pill, .status-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 900; }
        .pill { background: #fff6e8; color: var(--brown-dark); }
        .pill.waiter { background: #d8ebff; color: #1f4e7b; }
        .pill.cashier { background: #e6ffd9; color: #2c642b; }
        .pill.manager { background: #ffe2ad; color: #6b3d00; }
        .pill.owner { background: #f2d9ff; color: #59306d; }
        .pill.food { background: #ffe2ad; color: #6b3d00; }
        .pill.drink { background: #d8ebff; color: #1f4e7b; }
        .status-badge { background: rgba(126, 211, 134, .18); color: #c9f5ce; border: 1px solid rgba(126, 211, 134, .42); }
        .action-group { display: flex; gap: 8px; }
        .row-action { padding: 9px 11px; font-size: 13px; }
        .menu-thumb {
            width: 100%;
            aspect-ratio: 16 / 10;
            display: grid;
            place-items: center;
            border-radius: 8px;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(255, 246, 232, .18), rgba(255, 246, 232, .06)),
                rgba(255, 246, 232, .14);
            border: 1px solid rgba(255, 246, 232, .2);
            color: #fff8ed;
            font-size: 34px;
            font-weight: 900;
        }
        .menu-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .stock-badge {
            display: inline-flex;
            min-width: 42px;
            justify-content: center;
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 246, 232, .14);
            color: #fff8ed;
            font-weight: 900;
        }
        .menu-sections { display: grid; gap: 18px; margin-top: 16px; }
        .section-card {
            padding: 18px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
            border: 1px solid rgba(255, 246, 232, .22);
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .section-head { display: flex; justify-content: space-between; gap: 14px; align-items: flex-start; margin-bottom: 16px; }
        .section-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
        .section-action-row { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
        .section-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .section-add-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 999px;
            background: #fff6e8;
            color: var(--brown-dark);
            padding: 8px 12px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }
        .section-add-btn:hover {
            background: #fffdfa;
            color: var(--brown-dark);
            border-color: #fffdfa;
        }
        .section-secondary-btn {
            background: transparent;
            color: #fff8ed;
            border-color: rgba(255, 246, 232, .62);
        }
        .section-secondary-btn:hover,
        .section-manage-btn.is-active {
            background: #fff6e8;
            color: var(--brown-dark);
            border-color: rgba(255, 246, 232, .86);
        }
        .section-title { font-size: 24px; line-height: 1.08; font-weight: 900; }
        .section-subtitle { margin-top: 9px; color: rgba(255, 248, 237, .78); font-size: 14px; font-weight: 700; line-height: 1.45; }
        .section-meta {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            background: rgba(255, 246, 232, .12);
            color: #fff8ed;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 900;
        }
        .bulk-toolbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            padding: 11px 12px;
            border: 1px solid rgba(255, 246, 232, .2);
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            color: #fff8ed;
            font-weight: 900;
        }
        .bulk-toolbar.is-visible { display: flex; }
        .bulk-actions { display: flex; gap: 8px; }
        .bulk-cancel-btn,
        .bulk-delete-btn {
            border-radius: 7px;
            padding: 9px 11px;
            border: 1px solid rgba(255, 246, 232, .26);
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }
        .bulk-cancel-btn { background: rgba(255, 246, 232, .12); color: #fff8ed; }
        .bulk-delete-btn { background: #ffe2dc; color: #7b2418; }
        .menu-carousel {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) 44px;
            gap: 10px;
            align-items: center;
        }
        .menu-carousel-btn {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: center;
            padding: 0 0 3px;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 999px;
            background: #fff6e8;
            color: var(--brown-dark);
            font: inherit;
            font-size: 26px;
            line-height: 1;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(24, 13, 7, .18);
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }
        .menu-carousel-btn:hover {
            background: transparent;
            color: #fff8ed;
            transform: translateY(-1px);
        }
        .menu-rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 42px) / 4);
            gap: 14px;
            overflow-x: auto;
            overscroll-behavior-inline: contain;
            scroll-behavior: smooth;
            scroll-snap-type: x proximity;
            padding: 2px 2px 8px;
            cursor: grab;
            touch-action: pan-y;
        }
        .menu-rail.is-dragging {
            cursor: grabbing;
            scroll-behavior: auto;
            scroll-snap-type: none;
        }
        .menu-card {
            position: relative;
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 11px;
            min-width: 0;
            padding: 10px;
            border-radius: 8px;
            background: #fffdfa;
            border: 1px solid #ead4ba;
            color: var(--brown-dark);
            box-shadow: 0 12px 24px rgba(24, 13, 7, .18);
            scroll-snap-align: start;
            transition: border-color .2s ease, background .2s ease;
        }
        .menu-card.is-selected {
            background: #fff1ef;
            border-color: #e37a68;
        }
        .menu-select-control {
            position: absolute;
            left: 16px;
            top: 16px;
            z-index: 2;
            display: none;
            width: 26px;
            height: 26px;
            accent-color: #7b2418;
            cursor: pointer;
        }
        .section-card.is-managing .menu-select-control { display: block; }
        .menu-card-body { display: grid; gap: 9px; align-content: start; min-width: 0; }
        .menu-card-name {
            color: var(--brown-dark);
            font-size: 16px;
            font-weight: 900;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }
        .menu-card-price { color: var(--brown); font-size: 15px; font-weight: 900; }
        .menu-card-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .menu-card-actions .row-action { width: 100%; }
        .menu-card .menu-thumb {
            background:
                linear-gradient(135deg, rgba(154, 98, 57, .16), rgba(39, 20, 13, .05)),
                #f4e3cd;
            border-color: #ead4ba;
            color: var(--brown);
        }
        .menu-card .status-badge {
            background: #e6ffd9;
            color: #2c642b;
            border-color: #9ad28e;
        }
        .stock-current {
            display: grid;
            gap: 7px;
            padding: 10px;
            border-radius: 8px;
            background: #fff6e8;
            border: 1px solid #ead4ba;
        }
        .stock-current-label {
            color: #7a5a46;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .stock-current-value {
            color: var(--brown-dark);
            font-size: 18px;
            line-height: 1;
            font-weight: 900;
        }
        .stock-badge-status {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 11px;
            font-weight: 900;
        }
        .stock-badge-status.safe { background: #e6ffd9; color: #2c642b; border: 1px solid #9ad28e; }
        .stock-badge-status.low { background: #fff0b8; color: #755000; border: 1px solid #dfc25f; }
        .stock-badge-status.empty { background: #ffe2dc; color: #7b2418; border: 1px solid #e5a08d; }
        .stock-actions { grid-template-columns: 1fr; }
        .stock-modal-summary {
            display: grid;
            gap: 6px;
            padding: 13px;
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            border: 1px solid rgba(255, 246, 232, .2);
        }
        .stock-modal-product {
            color: #fff8ed;
            font-size: 18px;
            font-weight: 900;
        }
        .stock-modal-current {
            color: rgba(255, 248, 237, .78);
            font-size: 14px;
            font-weight: 800;
        }
        .stock-change-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }
        .stock-change-option {
            position: relative;
            display: block;
            align-items: center;
            min-height: 42px;
            border: 1px solid rgba(255, 246, 232, .28);
            border-radius: 8px;
            background: rgba(255, 246, 232, .08);
            color: #fff8ed;
            padding: 0;
            text-align: center;
            font-weight: 900;
            cursor: pointer;
            transition: background .2s ease, color .2s ease, border-color .2s ease, transform .2s ease;
        }
        .stock-change-option:hover {
            border-color: rgba(255, 246, 232, .68);
            transform: translateY(-1px);
        }
        .stock-change-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .stock-change-option:has(input:checked) {
            background: #fff6e8;
            border-color: #fff6e8;
            color: var(--brown-dark);
            box-shadow: 0 10px 22px rgba(24, 13, 7, .16);
        }
        .stock-change-option span {
            display: contents;
        }
        .stock-change-option b,
        .stock-change-option em {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-style: normal;
            font-weight: 900;
        }
        .stock-change-option b {
            left: 26%;
            width: 16px;
            text-align: center;
        }
        .stock-change-option em {
            left: calc(26% + 22px);
            text-align: left;
            white-space: nowrap;
        }
        .table-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }
        .table-card-item {
            position: relative;
            display: grid;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            background: #fffdfa;
            border: 1px solid #ead4ba;
            color: var(--brown-dark);
            box-shadow: 0 12px 24px rgba(24, 13, 7, .14);
        }
        .table-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .table-card-title {
            font-size: 17px;
            line-height: 1.2;
            font-weight: 900;
        }
        .table-more-wrap { position: relative; }
        .table-more-btn {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ead4ba;
            border-radius: 8px;
            background: #fff6e8;
            color: var(--brown-dark);
            font: inherit;
            font-size: 18px;
            line-height: 1;
            font-weight: 900;
            cursor: pointer;
        }
        .table-more-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            z-index: 5;
            display: none;
            min-width: 128px;
            padding: 6px;
            border-radius: 8px;
            background: #fffdfa;
            border: 1px solid #ead4ba;
            box-shadow: 0 12px 28px rgba(24, 13, 7, .18);
        }
        .table-more-wrap.is-open .table-more-menu { display: grid; }
        .table-menu-action {
            width: 100%;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #7b2418;
            padding: 9px 10px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            text-align: left;
            cursor: pointer;
        }
        .table-menu-action:hover { background: #ffe2dc; }
        .qr-box {
            display: grid;
            place-items: center;
            min-height: 132px;
            border-radius: 8px;
            background: #fff6e8;
            border: 1px solid #ead4ba;
            overflow: hidden;
        }
        .qr-box svg {
            width: 108px;
            height: 108px;
        }
        .table-link {
            color: #7a5a46;
            font-size: 12px;
            font-weight: 800;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .table-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .table-status {
            background: #e6ffd9;
            color: #1f5b1d;
            border: 1px solid #7fbe73;
        }
        .table-status.inactive {
            background: #ffe2dc;
            color: #7b2418;
            border-color: #e5a08d;
        }
        .qr-preview {
            display: grid;
            place-items: center;
            padding: 16px;
            border-radius: 8px;
            background: #fffdfa;
            border: 1px solid rgba(255, 246, 232, .22);
        }
        .qr-preview svg {
            width: min(260px, 100%);
            height: auto;
        }
        .qr-url {
            margin-top: 10px;
            color: rgba(255, 248, 237, .78);
            font-size: 13px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }
        .qr-modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }
        .menu-detail-thumb {
            width: 96px;
            height: 96px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 246, 232, .14);
            border: 1px solid rgba(255, 246, 232, .2);
            color: #fff8ed;
            font-size: 36px;
            font-weight: 900;
        }
        .feedback-stack { display: grid; gap: 8px; margin-bottom: 14px; }
        .success-banner, .error-banner {
            border-radius: 8px;
            padding: 12px 14px;
            font-weight: 900;
            cursor: pointer;
            transition: opacity .2s ease, transform .2s ease;
        }
        .success-banner.is-hidden, .error-banner.is-hidden {
            opacity: 0;
            transform: translateY(-4px);
            pointer-events: none;
        }
        .success-banner { background: #e6ffd9; color: #2c642b; border: 1px solid #9ad28e; }
        .error-banner { background: #ffe8dd; color: #7a2414; border: 1px solid #e5a08d; }
        .empty-state { padding: 28px; text-align: center; color: rgba(255, 248, 237, .78); }
        .pagination-wrap { display: flex; justify-content: flex-end; margin-top: 14px; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
        .pagination-info { margin-right: 4px; color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .page-link, .page-current { min-width: 38px; box-sizing: border-box; border: 1px solid rgba(255, 246, 232, .26); border-radius: 7px; padding: 9px 11px; color: #fff8ed; text-align: center; text-decoration: none; font-weight: 900; }
        .page-current { background: #fff6e8; color: var(--brown-dark); }
        .page-disabled { opacity: .45; }
        .panel { max-width: 820px; margin-top: 24px; background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 18px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); }
        .database-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-top: 16px; }
        .database-card {
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-height: 250px;
            padding: 18px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
            border: 1px solid rgba(255, 246, 232, .22);
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .database-card h2 { margin: 0; font-size: 22px; }
        .database-card p { color: rgba(255, 248, 237, .76); line-height: 1.5; }
        .database-card form { display: grid; gap: 10px; margin-top: auto; }
        .database-card input[type="file"],
        .database-card input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid rgba(255, 246, 232, .28);
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 12px 13px;
            font: inherit;
        }
        .database-note {
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            color: rgba(255, 248, 237, .78);
            font-size: 13px;
            font-weight: 800;
            line-height: 1.45;
        }
        .activity-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin: 16px 0; }
        .activity-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 246, 232, .24);
            border-radius: 999px;
            background: rgba(255, 246, 232, .1);
            color: #fff8ed;
            padding: 9px 13px;
            font-weight: 900;
            text-decoration: none;
        }
        .activity-tab.active { background: #fff6e8; border-color: #fff6e8; color: var(--brown-dark); }
        .change-action { text-transform: capitalize; }
        .restore-btn {
            border: 0;
            border-radius: 7px;
            background: #fff6e8;
            color: var(--brown-dark);
            padding: 9px 11px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }
        .restore-badge {
            display: inline-flex;
            border-radius: 999px;
            background: rgba(126, 211, 134, .18);
            border: 1px solid rgba(126, 211, 134, .42);
            color: #c9f5ce;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 900;
        }
        .modal-shell {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(39, 20, 13, .58);
        }
        .modal-shell.is-open { display: flex; }
        .modal-dialog {
            width: min(520px, 100%);
            overflow: hidden;
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
            box-shadow: 0 24px 60px rgba(24, 13, 7, .32);
            background-clip: padding-box;
        }
        .modal-dialog.menu-create-dialog {
            width: min(460px, 100%);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 18px;
            border-bottom: 1px solid rgba(255, 246, 232, .16);
        }
        .modal-title { font-size: 22px; font-weight: 900; }
        .modal-subtitle { margin-top: 4px; color: rgba(255, 248, 237, .78); font-size: 14px; line-height: 1.45; }
        .modal-close {
            width: 36px;
            height: 36px;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 8px;
            background: rgba(255, 246, 232, .9);
            color: var(--brown-dark);
            font: inherit;
            font-size: 18px;
            font-weight: 900;
            cursor: pointer;
        }
        .modal-close:hover {
            background: transparent;
            color: #fff8ed;
        }
        .modal-form { display: grid; gap: 14px; padding: 18px; background: transparent; }
        .menu-create-dialog .modal-header { padding: 15px 16px; }
        .menu-create-dialog .modal-form { gap: 10px; padding: 15px 16px 16px; }
        .menu-create-dialog .field-group { gap: 5px; }
        .menu-create-dialog .field-group input,
        .menu-create-dialog .field-group select { padding: 10px 12px; }
        .crop-panel { display: grid; gap: 10px; }
        .crop-box {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            border: 1px dashed rgba(255, 246, 232, .46);
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(255, 246, 232, .16), rgba(255, 246, 232, .06)),
                rgba(255, 246, 232, .1);
            color: rgba(255, 248, 237, .76);
            cursor: grab;
        }
        .menu-create-dialog .crop-box {
            aspect-ratio: 16 / 8.4;
            max-height: 220px;
        }
        .crop-box.is-dragging { cursor: grabbing; }
        .crop-empty {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 16px;
            text-align: center;
            font-size: 13px;
            font-weight: 900;
        }
        .crop-image {
            position: absolute;
            left: 50%;
            top: 50%;
            max-width: none;
            user-select: none;
            -webkit-user-drag: none;
            display: none;
            transform-origin: center;
        }
        .crop-tools { display: grid; gap: 5px; }
        .crop-tools label { color: rgba(255, 248, 237, .88); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .crop-tools input[type="range"] { width: 100%; }
        .field-group { display: grid; gap: 7px; }
        .field-group label { color: rgba(255, 248, 237, .88); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .field-group input, .field-group select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d9b48b;
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 12px 13px;
            font: inherit;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding-top: 4px;
        }
        .detail-list { display: grid; gap: 10px; padding: 18px; background: transparent; }
        .detail-row {
            display: grid;
            grid-template-columns: 170px 1fr;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 246, 232, .16);
        }
        .detail-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .detail-label { color: rgba(255, 248, 237, .72); font-size: 13px; font-weight: 900; }
        .detail-value { color: #fff8ed; font-weight: 900; overflow-wrap: anywhere; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 12px 14px;
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            color: #fff8ed;
            font-weight: 900;
        }
        .modal-dialog .table-wrap table { min-width: 0; }
        .modal-dialog .table-wrap th,
        .modal-dialog .table-wrap td { padding: 12px 14px; }
        .ghost-btn, .submit-btn {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #d9b48b;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
        }
        .ghost-btn { background: #fffdfa; color: var(--brown-dark); }
        .submit-btn { background: #fff6e8; color: var(--brown-dark); border-color: #fff6e8; }
        .ghost-btn:hover { background: var(--cream-soft); }
        .submit-btn:hover { background: transparent; color: #fff8ed; }
        @media (max-width: 1180px) { .menu-rail { grid-auto-columns: calc((100% - 28px) / 3); } }
        @media (max-width: 980px) { .summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .filter-form { grid-template-columns: 1fr; } .filter-actions { justify-content: stretch; } .filter-actions > * { flex: 1; } .menu-rail { grid-auto-columns: calc((100% - 14px) / 2); } .database-grid { grid-template-columns: 1fr; } .table-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .hero-card, .table-header, .section-head { align-items: flex-start; flex-direction: column; } .section-actions { width: 100%; justify-content: flex-start; } .summary-grid { grid-template-columns: 1fr; } .pagination-wrap { justify-content: flex-start; } .detail-row { grid-template-columns: 1fr; gap: 4px; } .menu-carousel { grid-template-columns: 1fr; } .menu-carousel-btn { display: none; } .menu-rail { grid-auto-columns: minmax(210px, 82vw); } .table-grid { grid-template-columns: 1fr; } .qr-modal-actions { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @if ($section === 'users')
                    @php
                        $roleLabels = [
                            1 => 'Waiter',
                            2 => 'Cashier',
                            3 => 'Manager',
                            4 => 'Owner',
                        ];

                        $roleClasses = [
                            1 => 'waiter',
                            2 => 'cashier',
                            3 => 'manager',
                            4 => 'owner',
                        ];
                    @endphp

                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data User</h1>
                                <p class="hero-subtitle">Kelola akun pengguna SwiftBite, lihat role yang digunakan, dan pantau user yang aktif di sistem bakery.</p>
                            </div>
                        </section>

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total User</div>
                                <div class="summary-value">{{ number_format($summary['total_user']) }}</div>
                                <div class="summary-note">Semua akun yang terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Waiter</div>
                                <div class="summary-value">{{ number_format($summary['waiter']) }}</div>
                                <div class="summary-note">Akun pengantaran pesanan</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Cashier</div>
                                <div class="summary-value">{{ number_format($summary['cashier']) }}</div>
                                <div class="summary-note">Akun kasir operasional</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Pengelola</div>
                                <div class="summary-value">{{ number_format($summary['pengelola']) }}</div>
                                <div class="summary-note">Manager dan owner</div>
                            </article>
                        </section>

                        <section class="table-card">
                            <div class="table-header">
                                <div>
                                    <div class="table-title">Manajemen User</div>
                                    <div class="table-subtitle">Menampilkan {{ number_format($users->total()) }} user berdasarkan filter aktif.</div>
                                </div>
                                <div class="table-header-actions">
                                    <button type="button" class="action-btn primary js-open-modal" data-modal="create-user">
                                        <span>Tambah User</span>
                                    </button>
                                </div>
                            </div>

                            @if (session('success') || $errors->any())
                                <div class="feedback-stack">
                                    @if (session('success'))
                                        <div class="success-banner">{{ session('success') }}</div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="error-banner">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <form method="GET" action="{{ route('manager.page', 'users') }}" class="filter-form">
                                <div class="filter-field">
                                    <label for="searchUser">Search User</label>
                                    <input id="searchUser" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama, username, atau email...">
                                </div>
                                <div class="filter-field">
                                    <label for="roleFilter">Filter Role</label>
                                    <select id="roleFilter" name="role">
                                        <option value="semua" @selected($filters['role'] === 'semua')>Semua Role</option>
                                        @foreach ($roleOptions as $level => $label)
                                            <option value="{{ $level }}" @selected($filters['role'] === (string) $level)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-actions">
                                    <button type="submit" class="filter-btn">
                                        <span>Terapkan</span>
                                    </button>
                                    <a href="{{ route('manager.page', 'users') }}" class="filter-link">
                                        <span>Reset</span>
                                    </a>
                                </div>
                            </form>

                            @if ($users->count() === 0)
                                <div class="empty-state">Belum ada data user yang cocok dengan filter saat ini.</div>
                            @else
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Dari</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                @php
                                                    $level = (int) $user->level;
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <div class="user-name">{{ $user->name }}</div>
                                                        <div class="user-meta">ID User: {{ $user->id_user ?? $user->id }}</div>
                                                    </td>
                                                    <td>{{ $user->username ?? $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="pill {{ $roleClasses[$level] ?? '' }}">{{ $roleLabels[$level] ?? 'Unknown' }}</span>
                                                    </td>
                                                    <td><span class="status-badge">Aktif</span></td>
                                                    <td>
                                                        <div class="action-group">
                                                            <button type="button" class="row-action js-open-modal" data-modal="edit-user-{{ $user->getKey() }}">Edit</button>
                                                            <button type="button" class="row-action js-open-modal" data-modal="detail-user-{{ $user->getKey() }}">Detail</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($users->hasPages())
                                    <div class="pagination-wrap">
                                        <div class="pagination">
                                            <span class="pagination-info">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>

                                            @if ($users->onFirstPage())
                                                <span class="page-link page-disabled">Prev</span>
                                            @else
                                                <a class="page-link" href="{{ $users->previousPageUrl() }}">Prev</a>
                                            @endif

                                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $pageNumber => $url)
                                                @if ($pageNumber === $users->currentPage())
                                                    <span class="page-current">{{ $pageNumber }}</span>
                                                @else
                                                    <a class="page-link" href="{{ $url }}">{{ $pageNumber }}</a>
                                                @endif
                                            @endforeach

                                            @if ($users->hasMorePages())
                                                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                                            @else
                                                <span class="page-link page-disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </section>

                        @foreach ($users as $user)
                            @php
                                $level = (int) $user->level;
                                $editModalId = 'edit-user-' . $user->getKey();
                                $detailModalId = 'detail-user-' . $user->getKey();
                            @endphp

                            <div class="modal-shell" id="modal-{{ $editModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalEditUserTitle{{ $user->getKey() }}">Edit User</div>
                                            <div class="modal-subtitle">Perbarui akun {{ $user->name }}. Nama user otomatis mengikuti username.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <form method="POST" action="{{ route('manager.users.update', $user) }}" class="modal-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="modal_id" value="{{ $editModalId }}">

                                        <div class="field-group">
                                            <label for="editUsername{{ $user->getKey() }}">Username</label>
                                            <input id="editUsername{{ $user->getKey() }}" type="text" name="username" value="{{ old('modal_id') === $editModalId ? old('username', $user->username ?? $user->name) : ($user->username ?? $user->name) }}" minlength="3" maxlength="15" required>
                                        </div>

                                        <div class="field-group">
                                            <label for="editPassword{{ $user->getKey() }}">Password Baru</label>
                                            <input id="editPassword{{ $user->getKey() }}" type="password" name="password" minlength="6" maxlength="20" placeholder="Kosongkan jika tidak diubah">
                                        </div>

                                        <div class="field-group">
                                            <label for="editRole{{ $user->getKey() }}">Role</label>
                                            <select id="editRole{{ $user->getKey() }}" name="level" required>
                                                <option value="1" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '1')>Waiter</option>
                                                <option value="2" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '2')>Cashier</option>
                                                <option value="3" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '3')>Manager</option>
                                                <option value="4" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '4')>Owner</option>
                                            </select>
                                        </div>

                                        <div class="modal-actions">
                                            <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                            <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal-shell" id="modal-{{ $detailModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailUserTitle{{ $user->getKey() }}">Detail User</div>
                                            <div class="modal-subtitle">Informasi akun {{ $user->name }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="detail-row">
                                            <div class="detail-label">Nama</div>
                                            <div class="detail-value">{{ $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Username</div>
                                            <div class="detail-value">{{ $user->username ?? $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Email</div>
                                            <div class="detail-value">{{ $user->email }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Role</div>
                                            <div class="detail-value">{{ $roleLabels[$level] ?? 'Unknown' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">Aktif</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Terakhir Diperbarui</div>
                                            <div class="detail-value">{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Login Terakhir</div>
                                            <div class="detail-value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-shell" id="modal-create-user" aria-hidden="true">
                        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title" id="modalCreateUserTitle">Tambah User</div>
                                    <div class="modal-subtitle">Buat akun baru untuk waiter atau cashier. Nama user otomatis mengikuti username.</div>
                                </div>
                                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                            </div>

                            <form method="POST" action="{{ route('manager.users.store') }}" class="modal-form">
                                @csrf
                                <input type="hidden" name="modal_id" value="create-user">
                                <div class="field-group">
                                    <label for="createUsername">Username</label>
                                    <input id="createUsername" type="text" name="username" value="{{ old('username') }}" minlength="3" maxlength="15" placeholder="Maksimal 15 karakter" required>
                                </div>

                                <div class="field-group">
                                    <label for="createPassword">Password</label>
                                    <input id="createPassword" type="password" name="password" minlength="6" maxlength="20" placeholder="6-20 karakter" required>
                                </div>

                                <div class="field-group">
                                    <label for="createRole">Role</label>
                                    <select id="createRole" name="level" required>
                                        <option value="" disabled @selected(old('level') === null)>Pilih role</option>
                                        <option value="1" @selected(old('level') === '1')>Waiter</option>
                                        <option value="2" @selected(old('level') === '2')>Cashier</option>
                                    </select>
                                </div>

                                <div class="modal-actions">
                                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                    <button type="submit" class="submit-btn">Simpan User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif ($section === 'tables')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data Meja</h1>
                                <p class="hero-subtitle">Kelola meja dan QR Code yang langsung membuka daftar makanan dan minuman customer.</p>
                            </div>
                        </section>

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Meja</div>
                                <div class="summary-value">{{ number_format($tableSummary['total']) }}</div>
                                <div class="summary-note">Meja yang terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Meja Aktif</div>
                                <div class="summary-value">{{ number_format($tableSummary['aktif']) }}</div>
                                <div class="summary-note">Bisa dipakai customer</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Meja Nonaktif</div>
                                <div class="summary-value">{{ number_format($tableSummary['nonaktif']) }}</div>
                                <div class="summary-note">Sedang tidak digunakan</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Pesanan Hari Ini</div>
                                <div class="summary-value">{{ number_format($tableSummary['today_orders']) }}</div>
                                <div class="summary-note">Pesanan dari customer</div>
                            </article>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <section class="section-card">
                            <div class="section-head">
                                <div>
                                    <div class="section-title-row">
                                        <div class="section-title">Daftar Meja</div>
                                        <div class="section-meta">{{ $tables->count() }} Meja</div>
                                    </div>
                                    <div class="section-subtitle">Customer cukup scan QR untuk langsung masuk ke menu makanan dan minuman.</div>
                                </div>
                                <div class="section-actions">
                                    <button type="button" class="section-add-btn js-open-modal" data-modal="create-table">+ Tambah Meja</button>
                                </div>
                            </div>

                            @if ($tables->isEmpty())
                                <div class="empty-state">Belum ada data meja.</div>
                            @else
                                <div class="table-grid">
                                    @foreach ($tables as $table)
                                        @php
                                            $tableActive = ! in_array($table->status, ['nonaktif', 'inactive'], true);
                                            $tableUrl = route('customer.menu', $table->qr_token);
                                            $tablePath = parse_url($tableUrl, PHP_URL_PATH) ?: $tableUrl;
                                        @endphp

                                        <article class="table-card-item">
                                            <div class="table-card-head">
                                                <div class="table-card-title">{{ $table->nama_meja }}</div>
                                                <div class="table-more-wrap">
                                                    <button type="button" class="table-more-btn js-table-more" aria-label="Menu {{ $table->nama_meja }}">⋮</button>
                                                    <div class="table-more-menu">
                                                        <form method="POST" action="{{ route('manager.tables.destroy', $table) }}" class="js-delete-table-form" data-table-name="{{ $table->nama_meja }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="table-menu-action">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="qr-box">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(108)->margin(1)->generate($tableUrl) !!}
                                            </div>
                                            <div class="table-link">{{ $tablePath }}</div>
                                            <div><span class="status-badge table-status {{ $tableActive ? '' : 'inactive' }}">{{ $tableActive ? 'Aktif' : 'Nonaktif' }}</span></div>
                                            <div class="table-card-actions">
                                                <button
                                                    type="button"
                                                    class="row-action js-open-modal js-table-qr"
                                                    data-modal="table-qr"
                                                    data-name="{{ $table->nama_meja }}"
                                                    data-url="{{ $tableUrl }}"
                                                >Lihat QR</button>
                                                <button
                                                    type="button"
                                                    class="row-action js-open-modal js-edit-table"
                                                    data-modal="edit-table"
                                                    data-action="{{ route('manager.tables.update', $table) }}"
                                                    data-name="{{ $table->nama_meja }}"
                                                    data-status="{{ $tableActive ? 'aktif' : 'nonaktif' }}"
                                                >Edit</button>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>

                        <div class="modal-shell" id="modal-create-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreateTableTitle">Tambah Meja</div>
                                        <div class="modal-subtitle">Buat meja baru dan token QR akan dibuat otomatis.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('manager.tables.store') }}" class="modal-form">
                                    @csrf
                                    <input type="hidden" name="modal_id" value="create-table">
                                    <div class="field-group">
                                        <label for="createTableName">Nama Meja</label>
                                        <input id="createTableName" type="text" name="name" value="{{ old('modal_id') === 'create-table' ? old('name') : '' }}" maxlength="7" placeholder="Contoh: Meja 8" required>
                                    </div>
                                    <div class="field-group">
                                        <label for="createTableStatus">Status</label>
                                        <select id="createTableStatus" name="status" required>
                                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Meja</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-edit-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalEditTableTitle">Edit Meja</div>
                                        <div class="modal-subtitle">Perbarui nama dan status meja.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <form method="POST" action="#" class="modal-form js-table-edit-form">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="modal_id" value="edit-table">
                                    <div class="field-group">
                                        <label for="editTableName">Nama Meja</label>
                                        <input id="editTableName" type="text" name="name" maxlength="7" placeholder="Contoh: Meja 8" required>
                                    </div>
                                    <div class="field-group">
                                        <label for="editTableStatus">Status</label>
                                        <select id="editTableStatus" name="status" required>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-table-qr" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalTableQrTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalTableQrTitle">QR Meja</div>
                                        <div class="modal-subtitle js-table-qr-subtitle">Scan QR untuk langsung membuka menu customer.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <div class="modal-form">
                                    <div class="qr-preview js-table-qr-preview"></div>
                                    <div class="qr-url js-table-qr-url">-</div>
                                    <div class="modal-actions qr-modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Tutup</button>
                                        <button type="button" class="submit-btn js-download-table-qr">Download PNG</button>
                                        <button type="button" class="submit-btn js-print-table-qr">Cetak QR</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-delete-table" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDeleteTableTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalDeleteTableTitle">Hapus Meja?</div>
                                        <div class="modal-subtitle">Meja yang dihapus tidak akan tampil di Data Meja.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>
                                <div class="modal-form">
                                    <div class="stock-modal-summary">
                                        <div class="stock-modal-product js-delete-table-name">-</div>
                                        <div class="stock-modal-current">Pastikan meja ini memang tidak digunakan lagi.</div>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="button" class="submit-btn js-confirm-delete-table">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($section === 'stock')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Stok Produk</h1>
                                <p class="hero-subtitle">Pantau dan kelola jumlah stok makanan dan minuman SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @php
                            $renderStockSection = function ($title, $description, $items) {
                                return compact('title', 'description', 'items');
                            };

                            $stockSections = [
                                $renderStockSection('Makanan', 'Kelola stok produk makanan dan bakery.', $foodStockItems),
                                $renderStockSection('Minuman', 'Kelola stok produk minuman.', $drinkStockItems),
                            ];
                        @endphp

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Produk</div>
                                <div class="summary-value">{{ number_format($stockSummary['total_produk']) }}</div>
                                <div class="summary-note">Produk yang memiliki stok</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Stok Aman</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_aman']) }}</div>
                                <div class="summary-note">Lebih dari 5 pcs</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Stok Menipis</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_menipis']) }}</div>
                                <div class="summary-note">1 sampai 5 pcs</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Stok Habis</div>
                                <div class="summary-value">{{ number_format($stockSummary['stok_habis']) }}</div>
                                <div class="summary-note">0 pcs</div>
                            </article>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="menu-sections">
                            @foreach ($stockSections as $stockSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title-row">
                                                <div class="section-title">{{ $stockSection['title'] }}</div>
                                                <div class="section-meta">{{ $stockSection['items']->count() }} Produk</div>
                                            </div>
                                            <div class="section-subtitle">{{ $stockSection['description'] }}</div>
                                        </div>
                                    </div>

                                    @if ($stockSection['items']->isEmpty())
                                        <div class="empty-state">Belum ada produk {{ strtolower($stockSection['title']) }}.</div>
                                    @else
                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser stok {{ $stockSection['title'] }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($stockSection['items'] as $menu)
                                                    @php
                                                        $stock = (int) $menu->stok;
                                                        $stockStatus = $stock <= 0 ? 'Habis' : ($stock <= 5 ? 'Menipis' : 'Aman');
                                                        $stockClass = $stock <= 0 ? 'empty' : ($stock <= 5 ? 'low' : 'safe');
                                                        $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                                    @endphp

                                                    <article class="menu-card">
                                                        <div class="menu-thumb">
                                                            @if ($menu->foto)
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="stock-current">
                                                                <div class="stock-current-label">Stok Saat Ini</div>
                                                                <div class="stock-current-value">{{ number_format($stock) }} pcs</div>
                                                                <div><span class="stock-badge-status {{ $stockClass }}">{{ $stockStatus }}</span></div>
                                                            </div>
                                                        </div>

                                                        <div class="menu-card-actions stock-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-stock-menu"
                                                                data-modal="stock-menu"
                                                                data-action="{{ route('manager.stock.update', $menu) }}"
                                                                data-name="{{ $menu->nama_menu }}"
                                                                data-stock="{{ $stock }}"
                                                            >Kelola Stok</button>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser stok {{ $stockSection['title'] }} ke kanan">&rsaquo;</button>
                                        </div>
                                    @endif
                                </section>
                            @endforeach
                        </div>

                        <div class="modal-shell" id="modal-stock-menu" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalStockMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalStockMenuTitle">Kelola Stok</div>
                                        <div class="modal-subtitle js-stock-menu-subtitle">Perbarui jumlah stok produk.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="#" class="modal-form js-stock-form">
                                    @csrf
                                    @method('patch')

                                    <div class="stock-modal-summary">
                                        <div class="stock-modal-product js-stock-product-name">-</div>
                                        <div class="stock-modal-current">Stok saat ini: <span class="js-stock-current">0</span> pcs</div>
                                    </div>

                                    <div class="field-group">
                                        <label>Jenis Perubahan</label>
                                        <div class="stock-change-options">
                                            <label class="stock-change-option" for="stockChangeAdd">
                                                <input id="stockChangeAdd" type="radio" name="change_type" value="add" checked>
                                                <span><b>+</b><em>Tambah Stok</em></span>
                                            </label>
                                            <label class="stock-change-option" for="stockChangeSubtract">
                                                <input id="stockChangeSubtract" type="radio" name="change_type" value="subtract">
                                                <span><b>-</b><em>Kurangi Stok</em></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="stockAmountInput">Jumlah Perubahan</label>
                                        <input id="stockAmountInput" type="number" name="amount" min="1" max="999" step="1" placeholder="Contoh: 5" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="stockNote">Keterangan (Opsional)</label>
                                        <input id="stockNote" type="text" name="note" maxlength="120" placeholder="Contoh: Restock pagi, penyesuaian stok">
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Stok</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif ($section === 'menus')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data Menu</h1>
                                <p class="hero-subtitle">Kelola makanan dan minuman yang tersedia di SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @php
                            $renderMenuSection = function ($title, $description, $items) {
                                return compact('title', 'description', 'items');
                            };

                            $menuSections = [
                                $renderMenuSection('Makanan', 'Daftar menu makanan dan bakery yang tersedia.', $foodMenuItems),
                                $renderMenuSection('Minuman', 'Daftar minuman yang tersedia.', $drinkMenuItems),
                            ];
                        @endphp

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Menu</div>
                                <div class="summary-value">{{ number_format($menuSummary['total_menu']) }}</div>
                                <div class="summary-note">Semua menu terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Total Makanan</div>
                                <div class="summary-value">{{ number_format($menuSummary['makanan']) }}</div>
                                <div class="summary-note">Roti, pastry, dan dessert</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Total Minuman</div>
                                <div class="summary-value">{{ number_format($menuSummary['minuman']) }}</div>
                                <div class="summary-note">Kopi dan non-kopi</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Menu Aktif</div>
                                <div class="summary-value">{{ number_format($menuSummary['aktif']) }}</div>
                                <div class="summary-note">Menu yang tersedia</div>
                            </article>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="menu-sections">
                            @foreach ($menuSections as $menuSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title-row">
                                                <div class="section-title">{{ $menuSection['title'] }}</div>
                                                <div class="section-meta">{{ $menuSection['items']->count() }} Menu</div>
                                            </div>
                                            <div class="section-subtitle">{{ $menuSection['description'] }}</div>
                                        </div>
                                        <div class="section-actions">
                                            <button type="button" class="section-add-btn js-open-modal" data-modal="create-menu" data-category="{{ $menuSection['title'] }}">
                                                + Tambah {{ $menuSection['title'] }}
                                            </button>
                                            <button type="button" class="section-add-btn section-secondary-btn section-manage-btn js-toggle-menu-manage">
                                                Kelola Menu
                                            </button>
                                        </div>
                                    </div>

                                    @if ($menuSection['items']->isEmpty())
                                        <div class="empty-state">Belum ada menu {{ strtolower($menuSection['title']) }}.</div>
                                    @else
                                        <form method="POST" action="{{ route('manager.menus.destroy') }}" class="js-bulk-delete-form">
                                            @csrf
                                            @method('delete')

                                            <div class="bulk-toolbar">
                                                <span><span class="js-selected-count">0</span> menu dipilih</span>
                                                <div class="bulk-actions">
                                                    <button type="button" class="bulk-cancel-btn js-cancel-menu-manage">Batal</button>
                                                    <button type="submit" class="bulk-delete-btn">Hapus Terpilih</button>
                                                </div>
                                            </div>

                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser {{ $menuSection['title'] }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($menuSection['items'] as $menu)
                                                    @php
                                                        $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                                        $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                                    @endphp

                                                    <article class="menu-card" data-menu-id="{{ $menu->getKey() }}" data-menu-name="{{ $menu->nama_menu }}" data-menu-category="{{ $menu->category }}" data-menu-price="Rp{{ number_format($menu->harga, 0, ',', '.') }}">
                                                        <input type="checkbox" class="menu-select-control js-menu-select" name="menu_ids[]" value="{{ $menu->getKey() }}" aria-label="Pilih {{ $menu->nama_menu }}">
                                                        <div class="menu-thumb">
                                                            @if ($menu->foto)
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="menu-card-price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                                            <div><span class="status-badge">{{ $statusLabel }}</span></div>
                                                        </div>

                                                        <div class="menu-card-actions">
                                                            <button
                                                                type="button"
                                                                class="row-action js-open-modal js-edit-menu"
                                                                data-modal="edit-menu"
                                                                data-action="{{ route('manager.menus.update', $menu) }}"
                                                                data-name="{{ $menu->nama_menu }}"
                                                                data-price="{{ (int) $menu->harga }}"
                                                                data-status="{{ $menu->status }}"
                                                                data-photo="{{ $menu->foto ? asset($menu->foto) : '' }}"
                                                            >Edit</button>
                                                            <button type="button" class="row-action js-single-delete-menu" data-menu-id="{{ $menu->getKey() }}">Hapus</button>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser {{ $menuSection['title'] }} ke kanan">&rsaquo;</button>
                                        </div>
                                        </form>
                                    @endif
                                </section>
                            @endforeach
                        </div>

                        <div class="modal-shell" id="modal-create-menu" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreateMenuTitle">Tambah Menu</div>
                                        <div class="modal-subtitle" id="modalCreateMenuSubtitle">Tambahkan menu baru ke SwiftBite Morning Bakery.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="{{ route('manager.menus.store') }}" class="modal-form js-menu-create-form">
                                    @csrf
                                    <input type="hidden" name="category" id="createMenuCategory" value="Makanan">
                                    <input type="hidden" name="image_data" class="js-cropped-image">

                                    <div class="field-group">
                                        <label for="createMenuPhoto">Gambar Menu</label>
                                        <input id="createMenuPhoto" type="file" class="js-crop-input" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="crop-panel">
                                        <div class="crop-box js-crop-box">
                                            <div class="crop-empty js-crop-empty">Pilih gambar, lalu geser area ini untuk menentukan crop.</div>
                                            <img class="crop-image js-crop-image" alt="Preview crop menu">
                                        </div>
                                        <div class="crop-tools">
                                            <label for="createMenuZoom">Zoom Gambar</label>
                                            <input id="createMenuZoom" type="range" class="js-crop-zoom" min="1" max="2.5" step="0.01" value="1" disabled>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuName">Nama <span class="js-menu-category-label">Makanan</span></label>
                                        <input id="createMenuName" type="text" name="name" value="{{ old('name') }}" maxlength="20" placeholder="Maksimal 20 karakter" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuPrice">Harga</label>
                                        <input id="createMenuPrice" type="number" name="price" value="{{ old('price') }}" min="0" max="50000" step="1000" placeholder="Maksimal 50000" required>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Menu</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('manager.menus.destroy') }}" class="js-single-delete-form" hidden>
                            @csrf
                            @method('delete')
                            <input type="hidden" name="menu_ids[]" class="js-single-delete-id">
                        </form>

                        <div class="modal-shell" id="modal-edit-menu" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalEditMenuTitle">Edit Menu</div>
                                        <div class="modal-subtitle">Perbarui gambar, nama, harga, dan status menu.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <form method="POST" action="#" class="modal-form js-menu-edit-form">
                                    @csrf
                                    @method('patch')
                                    <input type="hidden" name="image_data" class="js-edit-cropped-image">

                                    <div class="field-group">
                                        <label for="editMenuPhoto">Gambar Menu</label>
                                        <input id="editMenuPhoto" type="file" class="js-edit-crop-input" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="crop-panel">
                                        <div class="crop-box js-edit-crop-box">
                                            <div class="crop-empty js-edit-crop-empty">Gambar saat ini akan tetap dipakai jika tidak memilih gambar baru.</div>
                                            <img class="crop-image js-edit-crop-image" alt="Preview crop menu">
                                        </div>
                                        <div class="crop-tools">
                                            <label for="editMenuZoom">Zoom Gambar</label>
                                            <input id="editMenuZoom" type="range" class="js-edit-crop-zoom" min="1" max="2.5" step="0.01" value="1" disabled>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuName">Nama Menu</label>
                                        <input id="editMenuName" type="text" name="name" maxlength="20" placeholder="Maksimal 20 karakter" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuPrice">Harga</label>
                                        <input id="editMenuPrice" type="number" name="price" min="0" max="50000" step="1000" placeholder="Maksimal 50000" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="editMenuStatus">Status</label>
                                        <select id="editMenuStatus" name="status" required>
                                            <option value="tersedia">Aktif</option>
                                            <option value="habis">Nonaktif</option>
                                        </select>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="modal-shell" id="modal-confirm-delete-menu" aria-hidden="true">
                            <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalConfirmDeleteMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalConfirmDeleteMenuTitle">Hapus Menu?</div>
                                        <div class="modal-subtitle">Pastikan menu yang dipilih memang ingin dihapus.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                </div>

                                <div class="detail-list">
                                    <div class="summary-total" style="margin-bottom: 0;">
                                        <span><span class="js-delete-menu-count">0</span> menu akan dihapus.</span>
                                    </div>

                                    <div class="table-wrap">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Nama Menu</th>
                                                    <th>Kategori</th>
                                                    <th>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody class="js-delete-menu-list"></tbody>
                                        </table>
                                    </div>

                                    <div class="summary-total js-delete-menu-more" style="display: none; margin-top: 10px;">
                                        <span></span>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="button" class="submit-btn js-confirm-delete-menu">Hapus Terpilih</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @foreach ($foodMenuItems->concat($drinkMenuItems) as $menu)
                            @php
                                $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                $totalSold = (int) ($menu->total_sold ?? 0);
                            @endphp

                            <div class="modal-shell" id="modal-detail-menu-{{ $menu->getKey() }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailMenuTitle{{ $menu->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailMenuTitle{{ $menu->getKey() }}">Detail Menu</div>
                                            <div class="modal-subtitle">Informasi lengkap menu {{ $menu->nama_menu }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">&times;</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="menu-detail-thumb">{{ $initial }}</div>
                                        <div class="detail-row">
                                            <div class="detail-label">Nama Menu</div>
                                            <div class="detail-value">{{ $menu->nama_menu }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Kategori</div>
                                            <div class="detail-value">{{ $menu->category }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Harga</div>
                                            <div class="detail-value">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Stok Produk</div>
                                            <div class="detail-value">{{ $menu->stok }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Total Terjual</div>
                                            <div class="detail-value">{{ $totalSold }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">{{ $statusLabel }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Deskripsi</div>
                                            <div class="detail-value">{{ $menu->deskripsi ?: '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $menu->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Diperbarui</div>
                                            <div class="detail-value">{{ $menu->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif ($section === 'activity')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Monitoring</div>
                                <h1 class="hero-title">Catatan Aktivitas</h1>
                                <p class="hero-subtitle">Pantau aktivitas semua role dan perubahan data penting pada sistem SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <section class="table-card">
                            <div class="table-header">
                                <div>
                                    <div class="table-title">{{ $tab === 'data' ? 'Data Perubahan' : 'Aktivitas Pengguna' }}</div>
                                    <div class="table-subtitle">{{ $tab === 'data' ? 'Riwayat tambah, edit, hapus, dan pemulihan data.' : 'Riwayat aktivitas dari customer, waiter, cashier, manager, dan owner.' }}</div>
                                </div>
                            </div>

                            <div class="activity-tabs">
                                <a class="activity-tab {{ $tab === 'activity' ? 'active' : '' }}" href="{{ route('manager.page', ['section' => 'activity']) }}">Catatan Aktivitas</a>
                                <a class="activity-tab {{ $tab === 'data' ? 'active' : '' }}" href="{{ route('manager.page', ['section' => 'activity', 'tab' => 'data']) }}">Data Perubahan</a>
                            </div>

                            @if ($tab === 'activity')
                                <form class="filter-form" method="GET" action="{{ route('manager.page', ['section' => 'activity']) }}">
                                    <div class="filter-field">
                                        <label for="activityRoleFilter">Filter Role</label>
                                        <select id="activityRoleFilter" name="role">
                                            @foreach ($activityRoleOptions as $roleOption)
                                                <option value="{{ $roleOption }}" @selected($activityRole === $roleOption)>
                                                    {{ $roleOption === 'semua' ? 'Semua Role' : $roleOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="filter-actions">
                                        <button class="filter-btn" type="submit">Terapkan</button>
                                        <a class="filter-link" href="{{ route('manager.page', ['section' => 'activity']) }}">Reset</a>
                                    </div>
                                </form>

                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Dari</th>
                                                <th>Pengguna</th>
                                                <th>Aktivitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($activityLogs as $log)
                                                <tr>
                                                    <td>{{ $log->created_at?->format('d M Y H:i') }}</td>
                                                    <td><span class="pill">{{ $log->role }}</span></td>
                                                    <td>{{ $log->user_name ?: '-' }}</td>
                                                    <td>{{ $log->activity }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="empty-state">Belum ada catatan aktivitas.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if ($activityLogs->hasPages())
                                    <div class="pagination-wrap">{{ $activityLogs->links() }}</div>
                                @endif
                            @else
                                <form class="filter-form" method="GET" action="{{ route('manager.page', ['section' => 'activity']) }}">
                                    <input type="hidden" name="tab" value="data">
                                    <div class="filter-field">
                                        <label for="dataChangeFilter">Filter Aksi</label>
                                        <select id="dataChangeFilter" name="change">
                                            @foreach ($dataChangeOptions as $changeOption)
                                                <option value="{{ $changeOption }}" @selected($changeFilter === $changeOption)>
                                                    {{ $changeOption === 'semua' ? 'Semua' : $changeOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="filter-actions">
                                        <button class="filter-btn" type="submit">Terapkan</button>
                                        <a class="filter-link" href="{{ route('manager.page', ['section' => 'activity', 'tab' => 'data']) }}">Reset</a>
                                    </div>
                                </form>

                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                                <th>Data</th>
                                                <th>Nama Data</th>
                                                <th>Oleh</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataChanges as $change)
                                                <tr>
                                                    <td>{{ $change->created_at?->format('d M Y H:i') }}</td>
                                                    <td class="change-action">{{ $change->action }}</td>
                                                    <td>{{ $change->data_type }}</td>
                                                    <td>{{ $change->data_name }}</td>
                                                    <td>{{ $change->actor_role }}{{ $change->actor_name ? ' - '.$change->actor_name : '' }}</td>
                                                    <td>
                                                        @if ($change->restored_at)
                                                            <span class="restore-badge">Dipulihkan</span>
                                                        @else
                                                            <span class="pill">{{ $change->action === 'Hapus' ? 'Terhapus' : 'Aktif' }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (! $change->restored_at && in_array($change->data_type, ['Menu', 'User', 'Meja'], true))
                                                            <form method="POST" action="{{ route('manager.activity.restore', $change) }}">
                                                                @csrf
                                                                <button class="restore-btn" type="submit">{{ $change->action === 'Hapus' ? 'Pulihkan' : 'Kembalikan' }}</button>
                                                            </form>
                                                        @else
                                                            <span class="muted-text">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="empty-state">Belum ada data perubahan.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if ($dataChanges->hasPages())
                                    <div class="pagination-wrap">{{ $dataChanges->links() }}</div>
                                @endif
                            @endif
                        </section>
                    </div>
                @elseif ($section === 'database')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Sistem</div>
                                <h1 class="hero-title">Database</h1>
                                <p class="hero-subtitle">Kelola backup, import, dan reset data operasional SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <section class="database-grid">
                            <article class="database-card">
                                <div>
                                    <h2>Backup Database</h2>
                                    <p>Unduh file SQL berisi data utama aplikasi: user, meja, menu, pesanan, dan detail pesanan.</p>
                                </div>
                                <div class="database-note">Gunakan backup sebelum import atau reset data.</div>
                                <form method="POST" action="{{ route('manager.database.backup') }}">
                                    @csrf
                                    <button type="submit" class="submit-btn">Backup Sekarang</button>
                                </form>
                            </article>

                            <article class="database-card">
                                <div>
                                    <h2>Import Database</h2>
                                    <p>Upload file SQL hasil backup SwiftBite untuk mengembalikan data.</p>
                                </div>
                                <form method="POST" action="{{ route('manager.database.import') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="database_file" accept=".sql,.txt" required>
                                    <button type="submit" class="submit-btn">Import Database</button>
                                </form>
                            </article>

                            <article class="database-card">
                                <div>
                                    <h2>Reset Database</h2>
                                    <p>Mengosongkan data operasional: meja, menu, kategori, pesanan, dan detail pesanan. Akun user tetap disimpan.</p>
                                </div>
                                <div class="database-note">Ketik <strong>RESET DATABASE</strong> untuk konfirmasi.</div>
                                <form method="POST" action="{{ route('manager.database.reset') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="text" name="confirmation" placeholder="RESET DATABASE" required>
                                    <button type="submit" class="submit-btn">Reset Database</button>
                                </form>
                            </article>
                        </section>
                    </div>
                @else
                    <h1>{{ $page['title'] }}</h1>
                    <p class="muted">{{ $page['description'] }}</p>

                    <section class="panel">
                        <p class="muted">Halaman ini sudah disiapkan untuk fitur manager.</p>
                    </section>
                @endif
            </main>
        </div>
    </div>
    <script>
        (function () {
            const modalTriggers = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');
            const modals = document.querySelectorAll('.modal-shell');
            let pendingDeleteForm = null;
            let pendingDeleteTableForm = null;
            const tableQrState = {
                name: '',
                url: '',
            };
            const cropState = {
                image: null,
                box: null,
                img: null,
                zoomInput: null,
                hiddenInput: null,
                empty: null,
                baseWidth: 0,
                baseHeight: 0,
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                startOffsetX: 0,
                startOffsetY: 0,
                isDragging: false,
            };
            const editCropState = {
                image: null,
                box: null,
                img: null,
                zoomInput: null,
                hiddenInput: null,
                empty: null,
                baseWidth: 0,
                baseHeight: 0,
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                startOffsetX: 0,
                startOffsetY: 0,
                isDragging: false,
            };

            function prepareCreateMenuModal(trigger) {
                if (trigger.dataset.modal !== 'create-menu') {
                    return;
                }

                const category = trigger.dataset.category || 'Makanan';
                const title = document.getElementById('modalCreateMenuTitle');
                const subtitle = document.getElementById('modalCreateMenuSubtitle');
                const categoryInput = document.getElementById('createMenuCategory');
                const nameInput = document.getElementById('createMenuName');

                document.querySelectorAll('.js-menu-category-label').forEach((label) => {
                    label.textContent = category;
                });

                if (title) {
                    title.textContent = 'Tambah ' + category;
                }

                if (subtitle) {
                    subtitle.textContent = 'Tambahkan ' + category.toLowerCase() + ' baru ke SwiftBite Morning Bakery.';
                }

                if (categoryInput) {
                    categoryInput.value = category;
                }

                if (nameInput) {
                    nameInput.placeholder = category === 'Minuman' ? 'Contoh: Air Putih, maks. 20 karakter' : 'Contoh: Croissant, maks. 20 karakter';
                }
            }

            function prepareEditMenuModal(trigger) {
                if (trigger.dataset.modal !== 'edit-menu') {
                    return;
                }

                const form = document.querySelector('.js-menu-edit-form');
                const nameInput = document.getElementById('editMenuName');
                const priceInput = document.getElementById('editMenuPrice');
                const statusInput = document.getElementById('editMenuStatus');
                const fileInput = document.querySelector('.js-edit-crop-input');

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (priceInput) {
                    priceInput.value = trigger.dataset.price || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'tersedia';
                }

                if (fileInput) {
                    fileInput.value = '';
                }

                resetEditCropPreview(trigger.dataset.photo || '');
            }

            function prepareStockMenuModal(trigger) {
                if (trigger.dataset.modal !== 'stock-menu') {
                    return;
                }

                const form = document.querySelector('.js-stock-form');
                const title = document.getElementById('modalStockMenuTitle');
                const subtitle = document.querySelector('.js-stock-menu-subtitle');
                const productName = document.querySelector('.js-stock-product-name');
                const currentStock = document.querySelector('.js-stock-current');
                const amountInput = document.getElementById('stockAmountInput');
                const addInput = document.getElementById('stockChangeAdd');
                const noteInput = document.getElementById('stockNote');
                const name = trigger.dataset.name || 'Produk';
                const stock = trigger.dataset.stock || '0';

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (title) {
                    title.textContent = 'Kelola Stok';
                }

                if (subtitle) {
                    subtitle.textContent = 'Perbarui stok untuk ' + name + '.';
                }

                if (productName) {
                    productName.textContent = name;
                }

                if (currentStock) {
                    currentStock.textContent = stock;
                }

                if (amountInput) {
                    amountInput.value = '';
                    amountInput.max = '999';
                }

                if (addInput) {
                    addInput.checked = true;
                }

                if (noteInput) {
                    noteInput.value = '';
                }
            }

            function prepareEditTableModal(trigger) {
                if (trigger.dataset.modal !== 'edit-table') {
                    return;
                }

                const form = document.querySelector('.js-table-edit-form');
                const nameInput = document.getElementById('editTableName');
                const statusInput = document.getElementById('editTableStatus');

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'aktif';
                }
            }

            function prepareTableQrModal(trigger) {
                if (trigger.dataset.modal !== 'table-qr') {
                    return;
                }

                const title = document.getElementById('modalTableQrTitle');
                const subtitle = document.querySelector('.js-table-qr-subtitle');
                const preview = document.querySelector('.js-table-qr-preview');
                const urlText = document.querySelector('.js-table-qr-url');
                const card = trigger.closest('.table-card-item');
                const qrBox = card ? card.querySelector('.qr-box') : null;
                const name = trigger.dataset.name || 'Meja';
                const url = trigger.dataset.url || '#';
                tableQrState.name = name;
                tableQrState.url = url;

                if (title) {
                    title.textContent = 'QR ' + name;
                }

                if (subtitle) {
                    subtitle.textContent = 'Scan QR untuk langsung membuka menu customer ' + name + '.';
                }

                if (preview) {
                    preview.innerHTML = qrBox ? qrBox.innerHTML : '';
                }

                if (urlText) {
                    urlText.textContent = url;
                }

            }

            function tableQrFileName() {
                const baseName = (tableQrState.name || 'meja')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '') || 'meja';

                return 'swiftbite-' + baseName + '-qr.png';
            }

            function getTableQrSvg() {
                const preview = document.querySelector('.js-table-qr-preview');
                return preview ? preview.querySelector('svg') : null;
            }

            function escapeHtml(value) {
                const wrapper = document.createElement('div');
                wrapper.textContent = value || '';
                return wrapper.innerHTML;
            }

            function downloadTableQrPng() {
                const svg = getTableQrSvg();

                if (!svg) {
                    return;
                }

                const serializer = new XMLSerializer();
                const svgText = serializer.serializeToString(svg);
                const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
                const url = URL.createObjectURL(svgBlob);
                const image = new Image();

                image.onload = () => {
                    const size = 900;
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = size;
                    canvas.height = size;
                    context.fillStyle = '#ffffff';
                    context.fillRect(0, 0, size, size);
                    context.drawImage(image, 40, 40, size - 80, size - 80);
                    URL.revokeObjectURL(url);

                    const link = document.createElement('a');
                    link.download = tableQrFileName();
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                };

                image.src = url;
            }

            function printTableQr() {
                const svg = getTableQrSvg();

                if (!svg) {
                    return;
                }

                const name = tableQrState.name || 'Meja';
                const url = tableQrState.url || '';
                const svgText = new XMLSerializer().serializeToString(svg);
                const printWindow = window.open('', '_blank', 'width=520,height=720');
                const safeName = escapeHtml(name);
                const safeUrl = escapeHtml(url);

                if (!printWindow) {
                    return;
                }

                printWindow.document.write(`
                    <!doctype html>
                    <html lang="id">
                    <head>
                        <meta charset="utf-8">
                        <title>QR ${safeName}</title>
                        <style>
                            body { margin: 0; font-family: Arial, sans-serif; color: #2a1309; }
                            .sheet { min-height: 100vh; display: grid; place-items: center; padding: 32px; box-sizing: border-box; }
                            .card { width: 100%; max-width: 360px; text-align: center; border: 2px solid #5a2b17; border-radius: 16px; padding: 28px; }
                            .brand { font-size: 18px; font-weight: 800; margin-bottom: 8px; }
                            h1 { font-size: 34px; margin: 0 0 18px; }
                            svg { width: 260px; height: 260px; }
                            .url { margin-top: 18px; font-size: 12px; overflow-wrap: anywhere; }
                            @media print { .sheet { padding: 0; } .card { border-color: #000; } }
                        </style>
                    </head>
                    <body>
                        <main class="sheet">
                            <section class="card">
                                <div class="brand">SwiftBite Morning Bakery</div>
                                <h1>${safeName}</h1>
                                ${svgText}
                                <div class="url">${safeUrl}</div>
                            </section>
                        </main>
                    </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => printWindow.print(), 250);
            }

            function updateCropImage() {
                if (!cropState.image || !cropState.box || !cropState.img || !cropState.zoomInput) {
                    return;
                }

                const zoom = Number(cropState.zoomInput.value || 1);
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const width = cropState.baseWidth * zoom;
                const height = cropState.baseHeight * zoom;
                const maxOffsetX = Math.max(0, (width - boxWidth) / 2);
                const maxOffsetY = Math.max(0, (height - boxHeight) / 2);

                cropState.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, cropState.offsetX));
                cropState.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, cropState.offsetY));
                cropState.img.style.width = cropState.baseWidth + 'px';
                cropState.img.style.height = cropState.baseHeight + 'px';
                cropState.img.style.transform = 'translate(calc(-50% + ' + cropState.offsetX + 'px), calc(-50% + ' + cropState.offsetY + 'px)) scale(' + zoom + ')';
            }

            function setCropImage(file) {
                if (!file || !cropState.img || !cropState.box || !cropState.zoomInput) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = () => {
                    const image = new Image();

                    image.onload = () => {
                        const boxWidth = cropState.box.clientWidth;
                        const boxHeight = cropState.box.clientHeight;
                        const coverScale = Math.max(boxWidth / image.naturalWidth, boxHeight / image.naturalHeight);

                        cropState.image = image;
                        cropState.baseWidth = image.naturalWidth * coverScale;
                        cropState.baseHeight = image.naturalHeight * coverScale;
                        cropState.offsetX = 0;
                        cropState.offsetY = 0;
                        cropState.zoomInput.value = '1';
                        cropState.zoomInput.disabled = false;
                        cropState.img.src = reader.result;
                        cropState.img.style.display = 'block';

                        if (cropState.empty) {
                            cropState.empty.style.display = 'none';
                        }

                        updateCropImage();
                    };

                    image.src = reader.result;
                };

                reader.readAsDataURL(file);
            }

            function writeCroppedImage() {
                if (!cropState.image || !cropState.box || !cropState.hiddenInput || !cropState.zoomInput) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const zoom = Number(cropState.zoomInput.value || 1);
                const scale = (cropState.baseWidth * zoom) / cropState.image.naturalWidth;
                const imageLeft = (boxWidth - cropState.baseWidth * zoom) / 2 + cropState.offsetX;
                const imageTop = (boxHeight - cropState.baseHeight * zoom) / 2 + cropState.offsetY;
                const sourceX = Math.max(0, -imageLeft / scale);
                const sourceY = Math.max(0, -imageTop / scale);
                const sourceWidth = Math.min(cropState.image.naturalWidth - sourceX, boxWidth / scale);
                const sourceHeight = Math.min(cropState.image.naturalHeight - sourceY, boxHeight / scale);
                const outputWidth = 800;
                const outputHeight = 500;

                canvas.width = outputWidth;
                canvas.height = outputHeight;
                canvas.getContext('2d').drawImage(cropState.image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, outputWidth, outputHeight);
                cropState.hiddenInput.value = canvas.toDataURL('image/jpeg', .9);
            }

            function initMenuCropper() {
                cropState.box = document.querySelector('.js-crop-box');
                cropState.img = document.querySelector('.js-crop-image');
                cropState.zoomInput = document.querySelector('.js-crop-zoom');
                cropState.hiddenInput = document.querySelector('.js-cropped-image');
                cropState.empty = document.querySelector('.js-crop-empty');

                const fileInput = document.querySelector('.js-crop-input');
                const form = document.querySelector('.js-menu-create-form');

                if (!cropState.box || !cropState.img || !fileInput || !form) {
                    return;
                }

                fileInput.addEventListener('change', () => {
                    setCropImage(fileInput.files ? fileInput.files[0] : null);
                });

                cropState.zoomInput?.addEventListener('input', updateCropImage);

                cropState.box.addEventListener('pointerdown', (event) => {
                    if (!cropState.image || event.button !== 0) {
                        return;
                    }

                    cropState.isDragging = true;
                    cropState.startX = event.clientX;
                    cropState.startY = event.clientY;
                    cropState.startOffsetX = cropState.offsetX;
                    cropState.startOffsetY = cropState.offsetY;
                    cropState.box.classList.add('is-dragging');
                    cropState.box.setPointerCapture(event.pointerId);
                });

                cropState.box.addEventListener('pointermove', (event) => {
                    if (!cropState.isDragging) {
                        return;
                    }

                    event.preventDefault();
                    cropState.offsetX = cropState.startOffsetX + event.clientX - cropState.startX;
                    cropState.offsetY = cropState.startOffsetY + event.clientY - cropState.startY;
                    updateCropImage();
                });

                function stopCropDrag() {
                    cropState.isDragging = false;
                    cropState.box.classList.remove('is-dragging');
                }

                cropState.box.addEventListener('pointerup', stopCropDrag);
                cropState.box.addEventListener('pointercancel', stopCropDrag);
                form.addEventListener('submit', writeCroppedImage);
            }

            function resetEditCropPreview(photoUrl) {
                editCropState.image = null;
                editCropState.offsetX = 0;
                editCropState.offsetY = 0;

                if (editCropState.hiddenInput) {
                    editCropState.hiddenInput.value = '';
                }

                if (editCropState.zoomInput) {
                    editCropState.zoomInput.value = '1';
                    editCropState.zoomInput.disabled = true;
                }

                if (editCropState.img && photoUrl) {
                    editCropState.img.src = photoUrl;
                    editCropState.img.style.display = 'block';
                    editCropState.img.style.width = '100%';
                    editCropState.img.style.height = '100%';
                    editCropState.img.style.objectFit = 'cover';
                    editCropState.img.style.transform = 'none';
                    editCropState.img.style.left = '0';
                    editCropState.img.style.top = '0';
                } else if (editCropState.img) {
                    editCropState.img.removeAttribute('src');
                    editCropState.img.style.display = 'none';
                }

                if (editCropState.empty) {
                    editCropState.empty.style.display = photoUrl ? 'none' : 'grid';
                }
            }

            function updateEditCropImage() {
                if (!editCropState.image || !editCropState.box || !editCropState.img || !editCropState.zoomInput) {
                    return;
                }

                const zoom = Number(editCropState.zoomInput.value || 1);
                const boxWidth = editCropState.box.clientWidth;
                const boxHeight = editCropState.box.clientHeight;
                const width = editCropState.baseWidth * zoom;
                const height = editCropState.baseHeight * zoom;
                const maxOffsetX = Math.max(0, (width - boxWidth) / 2);
                const maxOffsetY = Math.max(0, (height - boxHeight) / 2);

                editCropState.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, editCropState.offsetX));
                editCropState.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, editCropState.offsetY));
                editCropState.img.style.left = '50%';
                editCropState.img.style.top = '50%';
                editCropState.img.style.objectFit = '';
                editCropState.img.style.width = editCropState.baseWidth + 'px';
                editCropState.img.style.height = editCropState.baseHeight + 'px';
                editCropState.img.style.transform = 'translate(calc(-50% + ' + editCropState.offsetX + 'px), calc(-50% + ' + editCropState.offsetY + 'px)) scale(' + zoom + ')';
            }

            function setEditCropImage(file) {
                if (!file || !editCropState.img || !editCropState.box || !editCropState.zoomInput) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = () => {
                    const image = new Image();

                    image.onload = () => {
                        const boxWidth = editCropState.box.clientWidth;
                        const boxHeight = editCropState.box.clientHeight;
                        const coverScale = Math.max(boxWidth / image.naturalWidth, boxHeight / image.naturalHeight);

                        editCropState.image = image;
                        editCropState.baseWidth = image.naturalWidth * coverScale;
                        editCropState.baseHeight = image.naturalHeight * coverScale;
                        editCropState.offsetX = 0;
                        editCropState.offsetY = 0;
                        editCropState.zoomInput.value = '1';
                        editCropState.zoomInput.disabled = false;
                        editCropState.img.src = reader.result;
                        editCropState.img.style.display = 'block';

                        if (editCropState.empty) {
                            editCropState.empty.style.display = 'none';
                        }

                        updateEditCropImage();
                    };

                    image.src = reader.result;
                };

                reader.readAsDataURL(file);
            }

            function writeEditCroppedImage() {
                if (!editCropState.image || !editCropState.box || !editCropState.hiddenInput || !editCropState.zoomInput) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const boxWidth = editCropState.box.clientWidth;
                const boxHeight = editCropState.box.clientHeight;
                const zoom = Number(editCropState.zoomInput.value || 1);
                const scale = (editCropState.baseWidth * zoom) / editCropState.image.naturalWidth;
                const imageLeft = (boxWidth - editCropState.baseWidth * zoom) / 2 + editCropState.offsetX;
                const imageTop = (boxHeight - editCropState.baseHeight * zoom) / 2 + editCropState.offsetY;
                const sourceX = Math.max(0, -imageLeft / scale);
                const sourceY = Math.max(0, -imageTop / scale);
                const sourceWidth = Math.min(editCropState.image.naturalWidth - sourceX, boxWidth / scale);
                const sourceHeight = Math.min(editCropState.image.naturalHeight - sourceY, boxHeight / scale);

                canvas.width = 800;
                canvas.height = 500;
                canvas.getContext('2d').drawImage(editCropState.image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, 800, 500);
                editCropState.hiddenInput.value = canvas.toDataURL('image/jpeg', .9);
            }

            function initEditMenuCropper() {
                editCropState.box = document.querySelector('.js-edit-crop-box');
                editCropState.img = document.querySelector('.js-edit-crop-image');
                editCropState.zoomInput = document.querySelector('.js-edit-crop-zoom');
                editCropState.hiddenInput = document.querySelector('.js-edit-cropped-image');
                editCropState.empty = document.querySelector('.js-edit-crop-empty');

                const fileInput = document.querySelector('.js-edit-crop-input');
                const form = document.querySelector('.js-menu-edit-form');

                if (!editCropState.box || !editCropState.img || !fileInput || !form) {
                    return;
                }

                fileInput.addEventListener('change', () => {
                    setEditCropImage(fileInput.files ? fileInput.files[0] : null);
                });

                editCropState.zoomInput?.addEventListener('input', updateEditCropImage);

                editCropState.box.addEventListener('pointerdown', (event) => {
                    if (!editCropState.image || event.button !== 0) {
                        return;
                    }

                    editCropState.isDragging = true;
                    editCropState.startX = event.clientX;
                    editCropState.startY = event.clientY;
                    editCropState.startOffsetX = editCropState.offsetX;
                    editCropState.startOffsetY = editCropState.offsetY;
                    editCropState.box.classList.add('is-dragging');
                    editCropState.box.setPointerCapture(event.pointerId);
                });

                editCropState.box.addEventListener('pointermove', (event) => {
                    if (!editCropState.isDragging) {
                        return;
                    }

                    event.preventDefault();
                    editCropState.offsetX = editCropState.startOffsetX + event.clientX - editCropState.startX;
                    editCropState.offsetY = editCropState.startOffsetY + event.clientY - editCropState.startY;
                    updateEditCropImage();
                });

                function stopEditCropDrag() {
                    editCropState.isDragging = false;
                    editCropState.box.classList.remove('is-dragging');
                }

                editCropState.box.addEventListener('pointerup', stopEditCropDrag);
                editCropState.box.addEventListener('pointercancel', stopEditCropDrag);
                form.addEventListener('submit', writeEditCroppedImage);
            }

            function openModal(id) {
                const modal = document.getElementById('modal-' + id);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

                if (!document.querySelector('.modal-shell.is-open')) {
                    document.body.style.overflow = '';
                }
            }

            modalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    prepareCreateMenuModal(trigger);
                    prepareEditMenuModal(trigger);
                    prepareStockMenuModal(trigger);
                    prepareEditTableModal(trigger);
                    prepareTableQrModal(trigger);
                    openModal(trigger.dataset.modal);
                });
            });

            document.querySelector('.js-download-table-qr')?.addEventListener('click', downloadTableQrPng);
            document.querySelector('.js-print-table-qr')?.addEventListener('click', printTableQr);

            document.querySelectorAll('.success-banner, .error-banner').forEach((banner) => {
                banner.addEventListener('click', () => {
                    banner.classList.add('is-hidden');
                    setTimeout(() => {
                        banner.remove();
                    }, 220);
                });
            });

            document.querySelectorAll('.js-table-more').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const wrap = button.closest('.table-more-wrap');

                    document.querySelectorAll('.table-more-wrap.is-open').forEach((opened) => {
                        if (opened !== wrap) {
                            opened.classList.remove('is-open');
                        }
                    });

                    wrap?.classList.toggle('is-open');
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.table-more-wrap.is-open').forEach((wrap) => {
                    wrap.classList.remove('is-open');
                });
            });

            document.querySelectorAll('.js-delete-table-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    pendingDeleteTableForm = form;

                    const name = document.querySelector('.js-delete-table-name');

                    if (name) {
                        name.textContent = form.dataset.tableName || 'Meja';
                    }

                    openModal('delete-table');
                });
            });

            document.querySelectorAll('.js-confirm-delete-table').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!pendingDeleteTableForm) {
                        return;
                    }

                    const form = pendingDeleteTableForm;
                    pendingDeleteTableForm = null;
                    form.submit();
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal-shell');

                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.querySelectorAll('.js-menu-scroll').forEach((button) => {
                button.addEventListener('click', () => {
                    const carousel = button.closest('.menu-carousel');
                    const rail = carousel ? carousel.querySelector('.menu-rail') : null;

                    if (!rail) {
                        return;
                    }

                    const direction = Number(button.dataset.direction || 1);
                    const firstCard = rail.querySelector('.menu-card');
                    const step = firstCard ? (firstCard.offsetWidth + 14) * 2 : 520;

                    rail.scrollBy({
                        left: direction * step,
                        behavior: 'smooth',
                    });
                });
            });

            document.querySelectorAll('.menu-rail').forEach((rail) => {
                let isDragging = false;
                let startX = 0;
                let startScrollLeft = 0;

                function stopDrag() {
                    if (!isDragging) {
                        return;
                    }

                    isDragging = false;
                    rail.classList.remove('is-dragging');
                }

                rail.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0 || event.target.closest('button, a, input, select, textarea')) {
                        return;
                    }

                    isDragging = true;
                    startX = event.clientX;
                    startScrollLeft = rail.scrollLeft;
                    rail.classList.add('is-dragging');
                    rail.setPointerCapture(event.pointerId);
                });

                rail.addEventListener('pointermove', (event) => {
                    if (!isDragging) {
                        return;
                    }

                    event.preventDefault();
                    const walk = event.clientX - startX;
                    rail.scrollLeft = startScrollLeft - walk;
                });

                rail.addEventListener('pointerup', stopDrag);
                rail.addEventListener('pointercancel', stopDrag);
                rail.addEventListener('pointerleave', stopDrag);
            });

            function updateBulkToolbar(section) {
                const selected = section.querySelectorAll('.js-menu-select:checked');
                const toolbar = section.querySelector('.bulk-toolbar');
                const count = section.querySelector('.js-selected-count');

                section.querySelectorAll('.menu-card').forEach((card) => {
                    const checkbox = card.querySelector('.js-menu-select');
                    card.classList.toggle('is-selected', Boolean(checkbox && checkbox.checked));
                });

                if (count) {
                    count.textContent = selected.length;
                }

                if (toolbar) {
                    toolbar.classList.toggle('is-visible', selected.length > 0);
                }
            }

            function openDeleteMenuModal(form, cards) {
                const count = cards.length;
                const list = document.querySelector('.js-delete-menu-list');
                const moreInfo = document.querySelector('.js-delete-menu-more');

                pendingDeleteForm = form;

                document.querySelectorAll('.js-delete-menu-count').forEach((element) => {
                    element.textContent = count;
                });

                if (list) {
                    list.innerHTML = '';
                    cards.slice(0, 5).forEach((card) => {
                        const row = document.createElement('tr');

                        [card.dataset.menuName || '-', card.dataset.menuCategory || '-', card.dataset.menuPrice || '-'].forEach((value) => {
                            const cell = document.createElement('td');
                            cell.textContent = value;
                            row.appendChild(cell);
                        });

                        list.appendChild(row);
                    });
                }

                if (moreInfo) {
                    const remaining = Math.max(0, count - 5);
                    const text = moreInfo.querySelector('span');

                    moreInfo.style.display = remaining > 0 ? 'flex' : 'none';

                    if (text) {
                        text.textContent = '+' + remaining + ' menu lainnya akan ikut dihapus.';
                    }
                }

                openModal('confirm-delete-menu');
            }

            document.querySelectorAll('.section-card').forEach((section) => {
                const manageButton = section.querySelector('.js-toggle-menu-manage');
                const cancelButton = section.querySelector('.js-cancel-menu-manage');
                const form = section.querySelector('.js-bulk-delete-form');

                manageButton?.addEventListener('click', () => {
                    const isManaging = section.classList.toggle('is-managing');
                    manageButton.classList.toggle('is-active', isManaging);
                    manageButton.textContent = isManaging ? 'Selesai Kelola' : 'Kelola Menu';

                    if (!isManaging) {
                        section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                            checkbox.checked = false;
                        });
                    }

                    updateBulkToolbar(section);
                });

                cancelButton?.addEventListener('click', () => {
                    section.classList.remove('is-managing');
                    manageButton?.classList.remove('is-active');

                    if (manageButton) {
                        manageButton.textContent = 'Kelola Menu';
                    }

                    section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                        checkbox.checked = false;
                    });

                    updateBulkToolbar(section);
                });

                section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                    checkbox.addEventListener('change', () => updateBulkToolbar(section));
                });

                form?.addEventListener('submit', (event) => {
                    const selected = Array.from(section.querySelectorAll('.js-menu-select:checked'));

                    event.preventDefault();

                    if (selected.length === 0) {
                        return;
                    }

                    openDeleteMenuModal(form, selected.map((checkbox) => checkbox.closest('.menu-card')).filter(Boolean));
                });
            });

            document.querySelectorAll('.js-confirm-delete-menu').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!pendingDeleteForm) {
                        return;
                    }

                    const form = pendingDeleteForm;
                    pendingDeleteForm = null;
                    form.submit();
                });
            });

            document.querySelectorAll('.js-single-delete-menu').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.querySelector('.js-single-delete-form');
                    const input = document.querySelector('.js-single-delete-id');
                    const card = button.closest('.menu-card');

                    if (!form || !input || !card) {
                        return;
                    }

                    input.value = button.dataset.menuId || '';
                    openDeleteMenuModal(form, [card]);
                });
            });

            initMenuCropper();
            initEditMenuCropper();

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    const modal = document.querySelector('.modal-shell.is-open');

                    if (modal) {
                        closeModal(modal);
                    }
                }
            });

            @if ($section === 'users' && $errors->any())
                openModal(@json(old('modal_id', 'create-user')));
            @endif

            @if ($section === 'menus' && $errors->any())
                prepareCreateMenuModal({ dataset: { modal: 'create-menu', category: @json(old('category', 'Makanan')) } });
                openModal('create-menu');
            @endif

            @if ($section === 'stock' && $errors->any())
                openModal('stock-menu');
            @endif

            @if ($section === 'tables' && $errors->any())
                openModal(@json(old('modal_id', 'create-table')));
            @endif
        })();
    </script>
</body>
</html>
