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
            aspect-ratio: 4 / 3;
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
            object-fit: contain;
            object-position: center center;
            display: block;
            background: #fffdfa;
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
        .package-card {
            grid-template-rows: auto 1fr auto;
        }
        .package-lines {
            display: grid;
            gap: 4px;
            color: #7a5a46;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.35;
        }
        .package-delete-form {
            display: grid;
        }
        .package-delete-form .row-action {
            width: 100%;
        }
        .menu-card .menu-thumb {
            background:
                linear-gradient(135deg, rgba(154, 98, 57, .16), rgba(39, 20, 13, .05)),
                #f4e3cd;
            border-color: #ead4ba;
            color: var(--brown);
        }
        .menu-card[data-menu-name="Donut Glazed"] .menu-thumb img {
            object-fit: cover;
            object-position: center bottom;
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
        .modal-dialog.package-create-dialog {
            width: min(620px, 100%);
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
        .package-picker {
            display: grid;
            gap: 10px;
            max-height: 260px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            background: rgba(255, 246, 232, .08);
        }
        .package-picker-group {
            display: grid;
            gap: 8px;
        }
        .package-picker-title {
            color: rgba(255, 248, 237, .88);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .package-picker-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 76px;
            gap: 10px;
            align-items: center;
            padding: 9px;
            border: 1px solid rgba(255, 246, 232, .18);
            border-radius: 8px;
            background: rgba(255, 253, 250, .08);
        }
        .package-picker-row span {
            display: grid;
            gap: 3px;
            min-width: 0;
        }
        .package-picker-row strong {
            color: #fff8ed;
            overflow-wrap: anywhere;
        }
        .package-picker-row em {
            color: rgba(255, 248, 237, .68);
            font-size: 12px;
            font-style: normal;
            font-weight: 800;
        }
        .package-picker-row input {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d9b48b;
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 10px 8px;
            font: inherit;
            font-weight: 900;
            text-align: center;
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
