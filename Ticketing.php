<?php require_once 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WildTrack Ticketing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2eb;
            min-height: 100vh;
        }

        /* ── PAGE SWITCHING ── */
        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        /* ── NAV ── */
        nav {
            background: #F9FBF7;
            border-bottom: 1px solid #e4e9e0;
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 700;
            color: #2D5A27;
        }

        .nav-back {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            border: 1.5px solid #e4e9e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            transition: all 0.2s;
            color: #2D5A27;
        }

        .nav-back:hover {
            background: #f0f4ee;
            border-color: #2D5A27;
        }

        .nav-bell {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e4e9e0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        /* ── LAYOUT ── */
        .page-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 40px 24px 120px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── HERO CARD ── */
        .hero-card {
            background: #F9FBF7;
            border-radius: 24px;
            padding: 28px 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .hero-card h1 {
            font-size: 26px;
            font-weight: 700;
            color: #2F3640;
            line-height: 1.3;
        }

        .hero-card h1 span {
            color: #2D5A27;
        }

        /* ── DATE SECTION ── */
        .section-card {
            background: #F9FBF7;
            border-radius: 24px;
            padding: 24px 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .section-card .section-title {
            font-size: 17px;
            font-weight: 600;
            color: #2F3640;
            margin-bottom: 16px;
        }

        .date-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .date-header h2 {
            font-size: 17px;
            font-weight: 600;
            color: #2F3640;
        }

        #currentMonth {
            color: #2D5A27;
            font-weight: 600;
            font-size: 15px;
        }

        .date-row {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .date-row::-webkit-scrollbar {
            display: none;
        }

        .date-card {
            min-width: 78px;
            height: 96px;
            background: #edf0ea;
            border-radius: 20px;
            text-align: center;
            padding-top: 12px;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .date-card p {
            font-size: 12px;
            font-weight: 600;
            color: #888;
        }

        .date-card h3 {
            font-size: 26px;
            font-weight: 700;
            color: #2F3640;
            margin-top: 4px;
        }

        .date-card.active {
            background: #4a7c4e;
            box-shadow: 0 6px 18px rgba(74, 124, 78, 0.3);
        }

        .date-card.active p {
            color: #c5e0c6;
        }

        .date-card.active h3 {
            color: #fff;
        }

        .date-card:hover:not(.active) {
            transform: scale(1.04);
            background: #e0e8dc;
        }

        /* ── TICKET CARDS ── */
        .ticket-card {
            background: #F9FBF7;
            border-radius: 20px;
            padding: 20px 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .ticket-card:hover {
            border-color: rgba(164, 198, 57, 0.35);
        }

        .ticket-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .ticket-name {
            font-size: 17px;
            font-weight: 600;
            color: #2F3640;
        }

        .ticket-sub {
            font-size: 12px;
            color: #7F8C8D;
            margin-top: 4px;
        }

        .ticket-price {
            font-size: 20px;
            font-weight: 700;
            color: #2D5A27;
        }

        .ticket-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge-orange {
            font-size: 11px;
            font-weight: 600;
            color: #E67E22;
            background: rgba(230, 126, 34, 0.1);
            padding: 4px 10px;
            border-radius: 8px;
        }

        .badge-green {
            font-size: 11px;
            font-weight: 600;
            color: #5a8a2f;
            background: rgba(164, 198, 57, 0.15);
            padding: 4px 10px;
            border-radius: 8px;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1.5px solid #d5d9d2;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2F3640;
            transition: all 0.15s;
        }

        .qty-btn:hover {
            border-color: #2D5A27;
            color: #2D5A27;
        }

        .qty-btn.plus {
            border-color: #2D5A27;
            background: rgba(45, 90, 39, 0.07);
            color: #2D5A27;
        }

        .qty-btn.plus:hover {
            background: #2D5A27;
            color: #fff;
        }

        .qty-num {
            font-size: 18px;
            font-weight: 700;
            color: #2F3640;
            min-width: 24px;
            text-align: center;
        }

        .ticket-card.family {
            background: linear-gradient(135deg, rgba(45, 90, 39, 0.06), rgba(164, 198, 57, 0.12));
            border-color: rgba(164, 198, 57, 0.25);
            position: relative;
            overflow: hidden;
        }

        .save-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #E67E22;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 5px 14px;
            border-bottom-left-radius: 12px;
        }

        .ticket-card.family .ticket-name {
            color: #2D5A27;
            font-weight: 700;
        }

        .family-check {
            font-size: 12px;
            font-weight: 600;
            color: #2D5A27;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .qty-btn.family-plus {
            background: #2D5A27;
            border-color: #2D5A27;
            color: #fff;
            box-shadow: 0 4px 10px rgba(45, 90, 39, 0.25);
        }

        .qty-btn.family-plus:hover {
            background: #245020;
        }

        /* ── ADD-ONS ── */
        .addons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 16px;
        }

        .addon-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            border: 1.5px solid transparent;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .addon-card.has-qty {
            border-color: #2D5A27;
            background: #f5faf3;
        }

        .addon-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .addon-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-green {
            background: rgba(164, 198, 57, 0.15);
            color: #2D5A27;
        }

        .icon-orange {
            background: rgba(230, 126, 34, 0.1);
            color: #E67E22;
        }

        .addon-info {
            flex: 1;
        }

        .addon-name {
            font-size: 13px;
            font-weight: 600;
            color: #2F3640;
        }

        .addon-price {
            font-size: 11px;
            color: #7F8C8D;
            margin-top: 2px;
        }

        .addon-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .addon-subtotal {
            font-size: 13px;
            font-weight: 700;
            color: #2D5A27;
            min-width: 52px;
        }

        .addon-qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .addon-qty-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1.5px solid #d5d9d2;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2F3640;
            transition: all 0.15s;
        }

        .addon-qty-btn:hover {
            border-color: #2D5A27;
            color: #2D5A27;
        }

        .addon-qty-btn.plus {
            border-color: #2D5A27;
            background: rgba(45, 90, 39, 0.07);
            color: #2D5A27;
        }

        .addon-qty-btn.plus:hover {
            background: #2D5A27;
            color: #fff;
        }

        .addon-qty-num {
            font-size: 16px;
            font-weight: 700;
            color: #2F3640;
            min-width: 20px;
            text-align: center;
        }

        /* ── BOTTOM BAR ── */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #F9FBF7;
            border-top: 1px solid #e4e9e0;
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.07);
            z-index: 99;
        }

        .bottom-total-label {
            font-size: 13px;
            color: #7F8C8D;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .bottom-total-value {
            font-size: 26px;
            font-weight: 700;
            color: #2D5A27;
        }

        .pay-btn {
            height: 52px;
            padding: 0 40px;
            background: #2D5A27;
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 18px rgba(45, 90, 39, 0.28);
            transition: all 0.2s;
        }

        .pay-btn:hover {
            background: #245020;
            transform: translateY(-1px);
        }

        .pay-btn:active {
            transform: scale(0.98);
        }

        /* ══════════════════════════════════════
           PAGE 2 — ORDER SUMMARY
        ══════════════════════════════════════ */

        /* Summary sections */
        .summary-block {
            background: #F9FBF7;
            border-radius: 20px;
            padding: 22px 26px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .summary-block-title {
            font-size: 13px;
            font-weight: 700;
            color: #7F8C8D;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f4ee;
            font-size: 14px;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            color: #7F8C8D;
        }

        .info-value {
            font-weight: 600;
            color: #2F3640;
        }

        .info-value.green {
            color: #2D5A27;
        }

        /* Payment methods */
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 4px;
        }

        @media (max-width: 540px) {
            .payment-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .pay-method {
            border: 2px solid #e4e9e0;
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }

        .pay-method:hover {
            border-color: #A4C639;
        }

        .pay-method.selected {
            border-color: #2D5A27;
            background: rgba(45, 90, 39, 0.05);
        }

        .pay-method-icon {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .pay-method-name {
            font-size: 12px;
            font-weight: 600;
            color: #2F3640;
        }

        .pay-method-sub {
            font-size: 10px;
            color: #7F8C8D;
            margin-top: 2px;
        }

        /* Grand total box */
        .grand-box {
            background: linear-gradient(135deg, #2D5A27, #3d7a35);
            border-radius: 20px;
            padding: 24px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 24px rgba(45, 90, 39, 0.25);
        }

        .grand-box-label {
            color: rgba(255, 255, 255, 0.75);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .grand-box-value {
            color: #fff;
            font-size: 32px;
            font-weight: 700;
        }

        .grand-box-note {
            color: rgba(255, 255, 255, 0.6);
            font-size: 11px;
            margin-top: 4px;
        }

        /* Confirm btn */
        .confirm-btn {
            width: 100%;
            height: 54px;
            background: #2D5A27;
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 18px rgba(45, 90, 39, 0.28);
            transition: all 0.2s;
        }

        .confirm-btn:hover {
            background: #245020;
            transform: translateY(-1px);
        }

        .confirm-btn:active {
            transform: scale(0.98);
        }

        /* Ticket item in summary */
        .ticket-summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f4ee;
        }

        .ticket-summary-item:last-child {
            border-bottom: none;
        }

        .ticket-summary-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ticket-summary-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(45, 90, 39, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2D5A27;
            font-size: 16px;
        }

        .ticket-summary-name {
            font-size: 14px;
            font-weight: 600;
            color: #2F3640;
        }

        .ticket-summary-qty {
            font-size: 12px;
            color: #7F8C8D;
            margin-top: 2px;
        }

        .ticket-summary-price {
            font-size: 15px;
            font-weight: 700;
            color: #2D5A27;
        }

        .divider {
            border: none;
            border-top: 1px solid #e4e9e0;
            margin: 4px 0;
        }

        /* Page transition */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideBack {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.3s ease both;
        }

        .slide-back {
            animation: slideBack 0.3s ease both;
        }
    </style>
</head>

<body>

    <!-- ═══════════════════════════════
     PAGE 1 — BOOKING
═══════════════════════════════ -->
    <div class="page active" id="page-booking">

        <nav>
            <div class="nav-left">
                <div class="nav-brand">
                    <span class="iconify" data-icon="lucide:tent-tree" style="font-size:24px;color:#2D5A27;"></span>
                    Ticket Sale
                </div>
            </div>
            <div class="nav-bell">
                <span class="iconify" data-icon="lucide:bell" style="font-size:18px;color:#2F3640;"></span>
            </div>
        </nav>

        <div class="page-wrap">

            <!-- Hero -->
            <div class="hero-card">
                <h1>Discover nature's <br><span>wildest moments</span></h1>
            </div>

            <!-- Date Picker -->
            <div class="section-card">
                <div class="date-header">
                    <h2>Select Date</h2>
                    <span id="currentMonth"></span>
                </div>
                <div class="date-row" id="dateRow"></div>
            </div>

            <!-- Tickets -->
            <div style="display:flex;flex-direction:column;gap:16px;">

                <!-- Adult -->
                <div class="ticket-card">
                    <div class="ticket-top">
                        <div>
                            <div class="ticket-name">Adult Pass</div>
                            <div class="ticket-sub">Age 18-64 • Full access</div>
                        </div>
                        <div class="ticket-price">RM20</div>
                    </div>
                    <div class="ticket-bottom">
                        <span class="badge-orange">Best Seller</span>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty('adult', -1)">
                                <span class="iconify" data-icon="lucide:minus" data-width="14"></span>
                            </button>
                            <span class="qty-num" id="qty-adult">1</span>
                            <button class="qty-btn plus" onclick="updateQty('adult', 1)">
                                <span class="iconify" data-icon="lucide:plus" data-width="14"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Child -->
                <div class="ticket-card">
                    <div class="ticket-top">
                        <div>
                            <div class="ticket-name">Child Pass</div>
                            <div class="ticket-sub">Age 4-17 • Under 4 free</div>
                        </div>
                        <div class="ticket-price">RM10</div>
                    </div>
                    <div class="ticket-bottom">
                        <span class="badge-green">Interactive Map Included</span>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty('child', -1)">
                                <span class="iconify" data-icon="lucide:minus" data-width="14"></span>
                            </button>
                            <span class="qty-num" id="qty-child">0</span>
                            <button class="qty-btn plus" onclick="updateQty('child', 1)">
                                <span class="iconify" data-icon="lucide:plus" data-width="14"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Family Bundle -->
                <div class="ticket-card family">
                    <div class="save-badge">SAVE 15%</div>
                    <div class="ticket-top">
                        <div>
                            <div class="ticket-name">Family Bundle</div>
                            <div class="ticket-sub">2 Adults + 2 Children</div>
                        </div>
                        <div class="ticket-price">RM50</div>
                    </div>
                    <div class="ticket-bottom">
                        <span class="family-check">
                            <span class="iconify" data-icon="lucide:check-circle" data-width="14"></span>
                            Priority Entry
                        </span>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty('family', -1)">
                                <span class="iconify" data-icon="lucide:minus" data-width="14"></span>
                            </button>
                            <span class="qty-num" id="qty-family">0</span>
                            <button class="qty-btn plus family-plus" onclick="updateQty('family', 1)">
                                <span class="iconify" data-icon="lucide:plus" data-width="14"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Add-ons -->
            <div class="section-card">
                <div class="section-title">Enhance Your Visit</div>
                <div class="addons-grid">

                    <!-- Safari Shuttle -->
                    <div class="addon-card" id="addon-card-safari">
                        <div class="addon-top">
                            <div class="addon-icon icon-green">
                                <span class="iconify" data-icon="lucide:bus"></span>
                            </div>
                            <div class="addon-info">
                                <div class="addon-name">Safari Shuttle</div>
                                <div class="addon-price">RM5 / person</div>
                            </div>
                        </div>
                        <div class="addon-bottom">
                            <span class="addon-subtotal" id="addon-subtotal-safari">RM0.00</span>
                            <div class="addon-qty-controls">
                                <button class="addon-qty-btn" onclick="updateAddon('safari', -1)">
                                    <span class="iconify" data-icon="lucide:minus" data-width="13"></span>
                                </button>
                                <span class="addon-qty-num" id="addon-qty-safari">0</span>
                                <button class="addon-qty-btn plus" onclick="updateAddon('safari', 1)">
                                    <span class="iconify" data-icon="lucide:plus" data-width="13"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Feeding Pass -->
                    <div class="addon-card" id="addon-card-feeding">
                        <div class="addon-top">
                            <div class="addon-icon icon-orange">
                                <span class="iconify" data-icon="lucide:cookie"></span>
                            </div>
                            <div class="addon-info">
                                <div class="addon-name">Feeding Pass</div>
                                <div class="addon-price">RM12 / person</div>
                            </div>
                        </div>
                        <div class="addon-bottom">
                            <span class="addon-subtotal" id="addon-subtotal-feeding">RM0.00</span>
                            <div class="addon-qty-controls">
                                <button class="addon-qty-btn" onclick="updateAddon('feeding', -1)">
                                    <span class="iconify" data-icon="lucide:minus" data-width="13"></span>
                                </button>
                                <span class="addon-qty-num" id="addon-qty-feeding">0</span>
                                <button class="addon-qty-btn plus" onclick="updateAddon('feeding', 1)">
                                    <span class="iconify" data-icon="lucide:plus" data-width="13"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="bottom-bar">
            <div>
                <div class="bottom-total-label">Total Price</div>
                <div class="bottom-total-value" id="total-price-bar">RM20.00</div>
            </div>
            <button class="pay-btn" onclick="goToSummary()">
                Pay
                <span class="iconify" data-icon="lucide:arrow-right" data-width="18"></span>
            </button>
        </div>

    </div>


    <!-- ═══════════════════════════════
     PAGE 2 — ORDER SUMMARY
═══════════════════════════════ -->
    <div class="page" id="page-summary">

        <nav>
            <div class="nav-left">
                <button class="nav-back" onclick="goBack()">
                    <span class="iconify" data-icon="lucide:arrow-left" data-width="18"></span>
                </button>
                <div class="nav-brand">
                    <span class="iconify" data-icon="lucide:tent-tree" style="font-size:22px;color:#2D5A27;"></span>
                    Order Summary
                </div>
            </div>
            <div class="nav-bell">
                <span class="iconify" data-icon="lucide:bell" style="font-size:18px;color:#2F3640;"></span>
            </div>
        </nav>

        <div class="page-wrap" style="padding-bottom: 40px;">

            <!-- Visit Info -->
            <div class="summary-block">
                <div class="summary-block-title">Visit Details</div>
                <div class="info-row">
                    <span class="info-label">📅 Visit Date</span>
                    <span class="info-value green" id="s-date">—</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🕘 Opening Hours</span>
                    <span class="info-value">9:00 AM – 6:00 PM</span>
                </div>
                <div class="info-row">
                    <span class="info-label">📍 Location</span>
                    <span class="info-value">WildTrack Safari Park</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🎟 Booking Ref</span>
                    <span class="info-value green" id="s-ref">—</span>
                </div>
            </div>

            <!-- Ticket Summary -->
            <div class="summary-block">
                <div class="summary-block-title">Tickets</div>
                <div id="s-tickets"><!-- filled by JS --></div>
                <hr class="divider" style="margin: 14px 0;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:#7F8C8D;">Total Visitors</span>
                    <span style="font-size:14px;font-weight:700;color:#2F3640;" id="s-visitors">—</span>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="summary-block">
                <div class="summary-block-title">Price Breakdown</div>
                <div id="s-price-rows"><!-- filled by JS --></div>
                <hr class="divider" style="margin:14px 0;">
                <div class="info-row" style="padding:0;">
                    <span class="info-label" style="font-size:15px;font-weight:600;color:#2F3640;">Total</span>
                    <span class="info-value green" style="font-size:20px;" id="s-total">—</span>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="summary-block">
                <div class="summary-block-title">Payment Method</div>
                <div class="payment-grid">
                    <div class="pay-method selected" onclick="selectPayment(this)">
                        <div class="pay-method-icon">💳</div>
                        <div class="pay-method-name">Credit / Debit</div>
                        <div class="pay-method-sub">Visa, Mastercard</div>
                    </div>
                    <div class="pay-method" onclick="selectPayment(this)">
                        <div class="pay-method-icon">🏦</div>
                        <div class="pay-method-name">Online Banking</div>
                        <div class="pay-method-sub">FPX, Maybank</div>
                    </div>
                    <div class="pay-method" onclick="selectPayment(this)">
                        <div class="pay-method-icon">📱</div>
                        <div class="pay-method-name">E-Wallet</div>
                        <div class="pay-method-sub">Touch 'n Go, GrabPay</div>
                    </div>
                    <div class="pay-method" onclick="selectPayment(this)">
                        <div class="pay-method-icon">🅿️</div>
                        <div class="pay-method-name">DuitNow QR</div>
                        <div class="pay-method-sub">Scan to Pay</div>
                    </div>
                    <div class="pay-method" onclick="selectPayment(this)">
                        <div class="pay-method-icon">🪙</div>
                        <div class="pay-method-name">Cash</div>
                        <div class="pay-method-sub">Pay at counter</div>
                    </div>
                    <div class="pay-method" onclick="selectPayment(this)">
                        <div class="pay-method-icon">🎁</div>
                        <div class="pay-method-name">Voucher</div>
                        <div class="pay-method-sub">Redeem code</div>
                    </div>
                </div>
            </div>

            <!-- Grand Total -->
            <div class="grand-box">
                <div>
                    <div class="grand-box-label">Amount to Pay</div>
                    <div class="grand-box-value" id="s-grand">RM0.00</div>
                    <div class="grand-box-note">Inclusive of all fees</div>
                </div>
                <span class="iconify" data-icon="lucide:shield-check"
                    style="font-size:48px;color:rgba(255,255,255,0.3);"></span>
            </div>

            <!-- Confirm Button -->
            <button class="confirm-btn" onclick="confirmPayment()">
                <span class="iconify" data-icon="lucide:lock" data-width="18"></span>
                Confirm & Pay
            </button>

            <p style="text-align:center;font-size:12px;color:#7F8C8D;margin-top:8px;">
                🔒 Secure payment · Instant e-ticket via email
            </p>

        </div>
    </div>


    <script>
    // ── Date Picker ──
    const dateRow   = document.getElementById("dateRow");
    const monthDisp = document.getElementById("currentMonth");
    const today     = new Date();
    let selectedDate = new Date();
    let selectedDateLabel = today.toLocaleDateString('en-MY', { weekday:'long', day:'numeric', month:'long', year:'numeric' });

    function generateWeek(startDate) {
        dateRow.innerHTML = "";
        for (let i = 0; i < 7; i++) {
            let date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            const dayName   = date.toLocaleDateString('en-US', { weekday: 'short' });
            const dayNumber = date.getDate();

            const card = document.createElement("div");
            card.classList.add("date-card");
            if (i === 0) card.classList.add("active");

            card.innerHTML = "<p>" + dayName + "</p><h3>" + dayNumber + "</h3>";
            card.addEventListener("click", function () {
                document.querySelectorAll(".date-card").forEach(c => c.classList.remove("active"));
                this.classList.add("active");
                selectedDate = new Date(date);
                monthDisp.textContent = date.toLocaleDateString('en-US', { month:'short', year:'numeric' });
                selectedDateLabel = date.toLocaleDateString('en-MY', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
            });
            dateRow.appendChild(card);
        }
        monthDisp.textContent = startDate.toLocaleDateString('en-US', { month:'short', year:'numeric' });
    }

    generateWeek(today);

    // ── Ticket Logic ──
    const prices     = { adult: 20, child: 10, family: 50 };
    const quantities = { adult: 1,  child: 0,  family: 0  };
    const labels     = { adult: 'Adult Pass', child: 'Child Pass', family: 'Family Bundle' };
    const icons      = { adult: 'lucide:user', child: 'lucide:baby', family: 'lucide:users' };

    // ── Addon Logic ──
    const addonPrices = { safari: 5, feeding: 12 };
    const addonQty    = { safari: 0, feeding: 0 };
    const addonLabels = { safari: 'Safari Shuttle', feeding: 'Feeding Pass' };
    const addonIcons  = { safari: 'lucide:bus', feeding: 'lucide:cookie' };

    function updateAddon(type, change) {
        const newQty = addonQty[type] + change;
        if (newQty >= 0) {
            addonQty[type] = newQty;
            document.getElementById("addon-qty-" + type).innerText = newQty;
            const subtotal = newQty * addonPrices[type];
            document.getElementById("addon-subtotal-" + type).innerText = "RM" + subtotal.toFixed(2);
            document.getElementById("addon-card-" + type).classList.toggle('has-qty', newQty > 0);
            calculateTotal();
        }
    }

    function updateQty(type, change) {
        const newQty = quantities[type] + change;
        if (newQty >= 0) {
            quantities[type] = newQty;
            document.getElementById("qty-" + type).innerText = newQty;
            calculateTotal();
        }
    }

    function calculateTotal() {
        let total = 0;
        total += quantities.adult  * prices.adult;
        total += quantities.child  * prices.child;
        total += quantities.family * prices.family;
        total += addonQty.safari   * addonPrices.safari;
        total += addonQty.feeding  * addonPrices.feeding;
        document.getElementById('total-price-bar').innerText = "RM" + total.toFixed(2);
        return total;
    }

    // ── Go To Summary Page ──
    function goToSummary() {
        const total = calculateTotal();
        const ref   = 'WT-' + Math.random().toString(36).substring(2,7).toUpperCase();

        document.getElementById('s-date').textContent = selectedDateLabel;
        document.getElementById('s-ref').textContent  = ref;
        document.getElementById('s-grand').textContent = "RM" + total.toFixed(2);
        document.getElementById('s-total').textContent = "RM" + total.toFixed(2);

        // Fill ticket lines
        const ticketContainer = document.getElementById('s-tickets');
        ticketContainer.innerHTML = '';
        let totalVisitors = 0;

        Object.keys(quantities).forEach(function(type) {
            const qty = quantities[type];
            if (qty === 0) return;
            if (type === 'family') totalVisitors += qty * 4;
            else totalVisitors += qty;

            ticketContainer.innerHTML += '<div class="ticket-summary-item">' +
                '<div class="ticket-summary-left">' +
                '<div class="ticket-summary-icon"><span class="iconify" data-icon="' + icons[type] + '" data-width="18"></span></div>' +
                '<div><div class="ticket-summary-name">' + labels[type] + '</div>' +
                '<div class="ticket-summary-qty">x ' + qty + ' &nbsp;.&nbsp; RM' + prices[type] + ' each</div></div>' +
                '</div>' +
                '<div class="ticket-summary-price">RM' + (qty * prices[type]).toFixed(2) + '</div>' +
                '</div>';
        });

        Object.keys(addonQty).forEach(function(type) {
            const qty = addonQty[type];
            if (qty === 0) return;
            ticketContainer.innerHTML += '<div class="ticket-summary-item">' +
                '<div class="ticket-summary-left">' +
                '<div class="ticket-summary-icon" style="background:rgba(230,126,34,0.1);color:#E67E22;">' +
                '<span class="iconify" data-icon="' + addonIcons[type] + '" data-width="18"></span></div>' +
                '<div><div class="ticket-summary-name">' + addonLabels[type] + '</div>' +
                '<div class="ticket-summary-qty">x ' + qty + ' pax &nbsp;.&nbsp; RM' + addonPrices[type] + ' each</div></div>' +
                '</div>' +
                '<div class="ticket-summary-price">RM' + (qty * addonPrices[type]).toFixed(2) + '</div>' +
                '</div>';
        });

        if (ticketContainer.innerHTML === '') {
            ticketContainer.innerHTML = '<p style="font-size:13px;color:#aaa;text-align:center;padding:12px 0;">No tickets selected.</p>';
        }

        document.getElementById('s-visitors').textContent = totalVisitors + ' pax';

        // Price breakdown
        const priceContainer = document.getElementById('s-price-rows');
        priceContainer.innerHTML = '';
        Object.keys(quantities).forEach(function(type) {
            const qty = quantities[type];
            if (qty === 0) return;
            priceContainer.innerHTML += '<div class="info-row">' +
                '<span class="info-label">' + labels[type] + ' x ' + qty + '</span>' +
                '<span class="info-value">RM' + (qty * prices[type]).toFixed(2) + '</span>' +
                '</div>';
        });
        Object.keys(addonQty).forEach(function(type) {
            const qty = addonQty[type];
            if (qty === 0) return;
            priceContainer.innerHTML += '<div class="info-row">' +
                '<span class="info-label">' + addonLabels[type] + ' x ' + qty + ' pax</span>' +
                '<span class="info-value">RM' + (qty * addonPrices[type]).toFixed(2) + '</span>' +
                '</div>';
        });

        document.getElementById('page-booking').classList.remove('active');
        document.getElementById('page-summary').classList.add('active');
        document.getElementById('page-summary').classList.add('slide-in');
        window.scrollTo(0, 0);
    }

    // ── Go Back ──
    function goBack() {
        document.getElementById('page-summary').classList.remove('active');
        document.getElementById('page-summary').classList.remove('slide-in');
        document.getElementById('page-booking').classList.add('active');
        document.getElementById('page-booking').classList.add('slide-back');
        window.scrollTo(0, 0);
        setTimeout(function() {
            document.getElementById('page-booking').classList.remove('slide-back');
        }, 400);
    }

    // ── Payment Method Selection ──
    function selectPayment(el) {
        document.querySelectorAll('.pay-method').forEach(function(m) {
            m.classList.remove('selected');
        });
        el.classList.add('selected');
    }

    // ── Confirm & Pay (saves to database) ──
   async function confirmPayment() {
    const selected = document.querySelector('.pay-method.selected .pay-method-name');
    const method   = selected ? selected.textContent : 'Unknown';

    // Read ticket quantities directly from the screen
    const adultQty  = parseInt(document.getElementById('qty-adult').innerText)  || 0;
    const childQty  = parseInt(document.getElementById('qty-child').innerText)  || 0;
    const familyQty = parseInt(document.getElementById('qty-family').innerText) || 0;
    const safariQty  = parseInt(document.getElementById('addon-qty-safari').innerText)  || 0;
    const feedingQty = parseInt(document.getElementById('addon-qty-feeding').innerText) || 0;

    const ticketsList = [];
    if (adultQty  > 0) ticketsList.push({ ticket_type: 'Adult', price: 20, quantity: adultQty  });
    if (childQty  > 0) ticketsList.push({ ticket_type: 'Child', price: 10, quantity: childQty  });
    if (familyQty > 0) ticketsList.push({ ticket_type: 'Group', price: 50, quantity: familyQty });

    if (ticketsList.length === 0) {
        alert('Please select at least one ticket.');
        return;
    }

    const addonsList = [];
    if (safariQty  > 0) addonsList.push({ addon_type: 'Safari Shuttle', quantity: safariQty,  price_per: 5  });
    if (feedingQty > 0) addonsList.push({ addon_type: 'Feeding Pass',   quantity: feedingQty, price_per: 12 });

    const visitDate = selectedDate instanceof Date
        ? selectedDate.toISOString().split('T')[0]
        : new Date().toISOString().split('T')[0];

    // Debug — check what we're sending
    console.log('Sending tickets:', ticketsList);
    console.log('Sending addons:', addonsList);
    console.log('Visit date:', visitDate);

    try {
        const res = await fetch('http://localhost/WildTrack/api/tickets.php?action=buy', {
            method:      'POST',
            credentials: 'include',
            headers:     { 'Content-Type': 'application/json' },
            body:        JSON.stringify({
                visit_date: visitDate,
                tickets:    ticketsList,
                addons:     addonsList
            })
        });

        const data = await res.json();
        console.log('Response from server:', data);

        if (data.success) {
            alert('Payment confirmed!\nMethod: ' + method + '\nTotal: RM' + data.total_paid.toFixed(2) + '\nTickets saved!');
        } else if (res.status === 401) {
            alert('Please log in first before purchasing tickets.');
        } else {
            alert('Error: ' + data.message);
        }

    } catch (err) {
        alert('Error: ' + err.message);
    }
}
</script>

</body>

</html>