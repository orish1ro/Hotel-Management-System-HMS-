<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt - Ragadio Plaza Hotel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="payment-body">

    <div class="receipt-container">
        <div class="receipt-card" id="printable-area">

            <div class="receipt-banner">
                <h2>RAGADIO PLAZA HOTEL</h2>
                <p>Official Transaction Receipt</p>
            </div>

            <div class="receipt-body">

                <div class="receipt-meta">
                    <div>
                        <p><strong>Receipt No:</strong> RPH-{{ 10000 + $data->PAYMENT_ID }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($data->Payment_Date)->format('F d, Y h:i A') }}</p>
                    </div>
                    <span class="status-paid">✓ Paid</span>
                </div>

                <div class="receipt-billed">
                    <h4>Billed To</h4>
                    <p><strong>{{ $data->First_Name }} {{ $data->Last_Name }}</strong></p>
                    <p>{{ $data->Email }}</p>
                </div>

                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>{{ $data->Room_Type }}</strong>
                                <small>Check-in: {{ \Carbon\Carbon::parse($data->Check_In_Date)->format('M d, Y') }}</small>
                                <small>Check-out: {{ \Carbon\Carbon::parse($data->Check_Out_Date)->format('M d, Y') }}</small>
                            </td>
                            <td>₱{{ number_format($data->Amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="receipt-summary">
                    <div class="summary-line">
                        <span>Payment Method</span>
                        <span>{{ $data->Payment_Method }}</span>
                    </div>
                    <div class="summary-line total">
                        <span>Total Paid</span>
                        <span>₱{{ number_format($data->Amount, 2) }}</span>
                    </div>
                </div>

                <div class="receipt-footer">
                    <p>Thank you for choosing Ragadio Plaza Hotel.</p>
                    <p>If you have any questions, please contact our front desk.</p>
                </div>

            </div>
        </div>

        <div class="receipt-actions">
            <button onclick="window.print()" class="btn-print">Print Receipt</button>
            <a href="/reservations" class="btn-back-home">View My Reservations</a>
        </div>
    </div>

</body>
</html>