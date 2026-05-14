<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #RPH-{{ 10000 + $data->PAYMENT_ID }} - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24px 16px;
        }

        .receipt-wrap {
            width: 100%;
            max-width: 480px;
        }

        /* ── Card ── */
        .receipt-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        }

        /* ── Header ── */
        .r-header {
            background: linear-gradient(135deg, #0f2952, #1a3f7a);
            padding: 20px 24px;
            text-align: center;
        }
        .r-header h2 {
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .r-header p {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        /* ── Body ── */
        .r-body { padding: 20px 24px; }

        /* Meta row */
        .r-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .r-meta p { font-size: 12px; color: #64748b; margin-bottom: 3px; }
        .r-meta p strong { color: #1e293b; }
        .badge-paid {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #86efac;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        /* Billed to */
        .r-billed {
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .r-label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #f59e0b;
            margin-bottom: 6px;
        }
        .r-billed .name { font-size: 14px; font-weight: 700; color: #1e293b; }
        .r-billed .email { font-size: 12px; color: #64748b; margin-top: 1px; }

        /* Items table */
        .r-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .r-items thead th {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #64748b;
            background: #f8fafc;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .r-items thead th:last-child { text-align: right; }
        .r-items tbody td {
            padding: 10px 10px;
            font-size: 13px;
            color: #334155;
            vertical-align: top;
            border-bottom: 1px solid #f1f5f9;
        }
        .r-items tbody td:last-child { text-align: right; font-weight: 700; color: #0f2952; }
        .r-items .sub { font-size: 11px; color: #94a3b8; margin-top: 2px; display: block; }

        /* Summary box */
        .r-summary {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
        }
        .r-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
            color: #64748b;
            padding: 4px 0;
        }
        .r-row.total {
            border-top: 1px solid #e2e8f0;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 14px;
            font-weight: 800;
            color: #0f2952;
        }
        .r-row.total span:last-child { color: #f59e0b; }

        /* Nights badge */
        .nights-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #1d4ed8;
            margin-top: 4px;
        }

        /* Footer note */
        .r-footer {
            text-align: center;
            padding-top: 12px;
            border-top: 1px dashed #e2e8f0;
        }
        .r-footer p { font-size: 11px; color: #94a3b8; line-height: 1.6; }

        /* Action buttons */
        .r-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }
        .btn-print {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px;
            background: linear-gradient(135deg, #0f2952, #1a3f7a);
            color: #fff; border: none; border-radius: 10px;
            font-size: 13px; font-weight: 700; cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.9; }
        .btn-back {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: #fff; border-radius: 10px; text-decoration: none;
            font-size: 13px; font-weight: 700;
            transition: opacity 0.2s;
        }
        .btn-back:hover { opacity: 0.9; }

        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 10mm; size: A4; }
            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                min-height: unset !important;
            }
            .receipt-wrap {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                border: none !important;
                width: 100% !important;
            }
            .r-header {
                background: linear-gradient(135deg, #0f2952, #1a3f7a) !important;
                -webkit-print-color-adjust: exact !important;
            }
            .r-actions { display: none !important; }
        }
    </style>
</head>
<body>

    @php
        $checkIn  = \Carbon\Carbon::parse($data->Check_In_Date);
        $checkOut = \Carbon\Carbon::parse($data->Check_Out_Date);
        $nights   = $checkIn->diffInDays($checkOut);
        $pricePerNight = $nights > 0 ? ($data->Amount / $nights) : $data->Amount;
    @endphp

    <div class="receipt-wrap">
        <div class="receipt-card" id="printable-area">

            {{-- Header --}}
            <div class="r-header">
                <h2>Ragadio Plaza Hotel</h2>
                <p>Official Transaction Receipt</p>
            </div>

            <div class="r-body">

                {{-- Meta --}}
                <div class="r-meta">
                    <div>
                        <p><strong>Receipt No:</strong> RPH-{{ 10000 + $data->PAYMENT_ID }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($data->Payment_Date)->format('M d, Y h:i A') }}</p>
                        <p><strong>Reservation:</strong> #{{ str_pad($data->RESERVATION_ID, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="badge-paid"><i class="fa-solid fa-circle-check"></i> PAID</span>
                </div>

                {{-- Billed To --}}
                <div class="r-billed">
                    <div class="r-label">Billed To</div>
                    <div class="name">{{ strtoupper($data->First_Name) }} {{ strtoupper($data->Last_Name) }}</div>
                    <div class="email">{{ $data->Email }}</div>
                </div>

                {{-- Items --}}
                <table class="r-items">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong style="color:#1e293b;">{{ $data->Room_Type }}</strong>
                                <span class="sub"><i class="fa-solid fa-arrow-right-to-bracket" style="font-size:10px;"></i> Check-in: {{ $checkIn->format('M d, Y') }}</span>
                                <span class="sub"><i class="fa-solid fa-arrow-right-from-bracket" style="font-size:10px;"></i> Check-out: {{ $checkOut->format('M d, Y') }}</span>
                                <span class="nights-badge">
                                    <i class="fa-solid fa-moon" style="font-size:10px;"></i>
                                    {{ $nights }} night{{ $nights != 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td>₱{{ number_format($data->Amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Summary --}}
                <div class="r-summary">
                    <div class="r-row">
                        <span>Payment Method</span>
                        <span style="font-weight:600; color:#334155;">{{ $data->Payment_Method }}</span>
                    </div>
                    <div class="r-row">
                        <span>Room Rate</span>
                        <span>₱{{ number_format($pricePerNight, 2) }} / night</span>
                    </div>
                    <div class="r-row">
                        <span>Nights</span>
                        <span>× {{ $nights }}</span>
                    </div>
                    <div class="r-row total">
                        <span>Total Paid</span>
                        <span>₱{{ number_format($data->Amount, 2) }}</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="r-footer">
                    <p>Thank you for choosing Ragadio Plaza Hotel.</p>
                    <p>Questions? Contact our front desk anytime.</p>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="r-actions">
            <button onclick="window.print()" class="btn-print">
                <i class="fa-solid fa-print"></i> Print Receipt
            </button>
            <a href="/reservations" class="btn-back">
                <i class="fa-solid fa-list"></i> My Reservations
            </a>
        </div>
    </div>

</body>
</html>