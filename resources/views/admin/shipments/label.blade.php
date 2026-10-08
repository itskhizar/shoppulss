<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - {{ $shipment->tracking_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .actions-bar {
            position: fixed;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        .btn {
            background-color: #0F1B4D;
            color: #fff;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn:hover {
            opacity: 0.95;
        }

        /* 4x6 Inch Standard Thermal Label Dimensions */
        .shipping-label {
            width: 400px;
            background: #ffffff;
            border: 2px solid #000;
            padding: 14px;
            color: #000;
            font-size: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .brand-title span {
            color: #00A8B8;
        }

        .courier-badge {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            border: 1.5px solid #000;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .barcode-section {
            text-align: center;
            padding: 10px 0 8px 0;
            border-bottom: 2px solid #000;
        }

        /* Realistic Barcode CSS pattern */
        .barcode-bars {
            height: 48px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                #fff 2px,
                #fff 4px,
                #000 4px,
                #000 7px,
                #fff 7px,
                #fff 9px,
                #000 9px,
                #000 13px,
                #fff 13px,
                #fff 15px
            );
            margin: 0 auto;
            width: 85%;
        }

        .barcode-text {
            font-family: monospace;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .routing-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 2px solid #000;
            padding: 6px 0;
            font-size: 11px;
        }

        .destination-box {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            text-align: right;
            padding-right: 4px;
        }

        .consignee-section {
            padding: 8px 0;
            border-bottom: 2px solid #000;
        }

        .section-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 2px;
        }

        .consignee-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .consignee-phone {
            font-size: 13px;
            font-weight: bold;
            font-family: monospace;
            margin-bottom: 4px;
        }

        .consignee-address {
            font-size: 11px;
            line-height: 1.35;
        }

        /* Big COD / Prepaid Highlight box */
        .payment-banner {
            border: 2px solid #000;
            margin: 8px 0;
            padding: 8px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .cod-amount {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            font-size: 10px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
            text-align: center;
        }

        .shipper-section {
            font-size: 9.5px;
            color: #333;
            line-height: 1.3;
            padding-top: 4px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                display: block;
            }
            .actions-bar {
                display: none;
            }
            .shipping-label {
                box-shadow: none;
                border: 2px solid #000;
                width: 100%;
                max-width: 380px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button class="btn" onclick="window.print()">🖨 Print Label</button>
        <button class="btn" style="background-color: #555;" onclick="window.close()">Close</button>
    </div>

    <div class="shipping-label">
        {{-- Header --}}
        <div class="header-row">
            <div class="brand-title">Shop<span>Pulss</span></div>
            <div class="courier-badge">{{ $shipment->courier?->name ?? 'EXPRESS LOGISTICS' }}</div>
        </div>

        {{-- Barcode --}}
        <div class="barcode-section">
            <div class="barcode-bars"></div>
            <div class="barcode-text">{{ $shipment->tracking_number }}</div>
        </div>

        {{-- Routing Header --}}
        <div class="routing-info">
            <div>
                <span class="section-label">Origin:</span>
                <div>Karachi Fulfillment</div>
            </div>
            <div>
                <span class="section-label">Destination City:</span>
                <div class="destination-box">{{ $shipment->destination_city }}</div>
            </div>
        </div>

        {{-- Consignee (Customer) --}}
        <div class="consignee-section">
            <div class="section-label">Deliver To (Consignee):</div>
            <div class="consignee-name">{{ $shipment->consignee_name }}</div>
            <div class="consignee-phone">📞 {{ $shipment->consignee_phone }}</div>
            <div class="consignee-address">{{ $shipment->consignee_address }}</div>
        </div>

        {{-- Cash Collection Box --}}
        <div class="payment-banner">
            @if($shipment->cod_amount > 0)
                <div class="section-label" style="color: #000; font-weight: 800;">★ CASH ON DELIVERY (COD) ★</div>
                <div class="cod-amount">CASH TO COLLECT: Rs. {{ number_format($shipment->cod_amount) }}</div>
            @else
                <div class="section-label" style="color: #000; font-weight: 800;">PREPAID ORDER</div>
                <div class="cod-amount" style="font-size: 16px;">DO NOT COLLECT CASH (ALREADY PAID)</div>
            @endif
        </div>

        {{-- Order Meta --}}
        <div class="order-meta">
            <div>
                <span class="section-label">Order #:</span>
                <strong style="display:block; font-size: 11px;">{{ $shipment->order?->order_number }}</strong>
            </div>
            <div>
                <span class="section-label">Weight:</span>
                <strong style="display:block; font-size: 11px;">{{ $shipment->weight }} KG</strong>
            </div>
            <div>
                <span class="section-label">Pieces:</span>
                <strong style="display:block; font-size: 11px;">{{ $shipment->pieces }} PC</strong>
            </div>
        </div>

        @if($shipment->notes)
            <div style="font-size: 10px; margin-bottom: 6px; padding: 4px; background: #eee; border-radius: 3px;">
                <strong>Remarks:</strong> {{ $shipment->notes }}
            </div>
        @endif

        {{-- Return / Shipper --}}
        <div class="shipper-section">
            <strong>If Undelivered, Return To:</strong><br>
            ShopPulss Central Dispatch Hub, Commercial Area, Pakistan.<br>
            Helpline: {{ \App\Models\Setting::get('store_phone', '+923328912706') }} | {{ \App\Models\Setting::get('store_email', 'devwordspace3300@gmail.com') }}
        </div>
    </div>

</body>
</html>
