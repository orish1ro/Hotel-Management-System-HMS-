<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Secure Payment - Ragadio Plaza Hotel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

.payment-body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background-color: #f1f5f9;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    -webkit-font-smoothing: antialiased;
}

.receipt-card {
    background: #fff;
    width: 100%;
    max-width: 940px;
    padding: 36px 45px;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    border: 1px solid #e2e8f0;
}

/* Header */
.receipt-header {
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
}
.receipt-header h2 { font-size: 22px; color: #003366; font-weight: 800; margin-bottom: 3px; }
.receipt-header p { font-size: 11px; color: #94a3b8; font-weight: 500; }

/* Two-column layout */
.pay-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    align-items: start;
}
.pay-col-left { padding-right: 40px; }
.pay-col-right { padding-left: 40px; border-left: 1px solid #e2e8f0; }

/* Hero */
.receipt-total-hero {
    text-align: center;
    padding: 18px 16px;
    background: #003366;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.receipt-total-hero::before {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 80px; height: 80px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.receipt-total-hero .hero-label {
    display: block;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    opacity: 0.65;
    margin-bottom: 4px;
}
.receipt-total-hero .amount { font-size: 30px; font-weight: 800; line-height: 1.1; }
.receipt-total-hero .hero-sub { display: block; font-size: 10px; opacity: 0.55; margin-top: 4px; }

/* Detail rows */
.receipt-details { margin-bottom: 12px; }
.receipt-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f8fafc;
    font-size: 12.5px;
}
.receipt-row:last-child { border-bottom: none; }
.receipt-row .label { color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 8px; }
.receipt-row .label i { width: 16px; text-align: center; color: #94a3b8; font-size: 12px; }
.receipt-row .value { font-weight: 700; color: #1e293b; }

/* Services */
.services-card {
    background: #f8fafc;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 14px;
    border: 1px solid #e2e8f0;
}
.services-title {
    display: block;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    margin-bottom: 8px;
}
.service-line { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; color: #334155; }
.service-line .price { color: #16a34a; font-weight: 700; }
.service-line.subtotal { margin-top: 8px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-weight: 700; }

/* Notice */
.notice-box {
    background: #fffbeb;
    border: 1.5px solid #fcd34d;
    border-radius: 8px;
    padding: 9px 12px;
    margin-bottom: 16px;
    font-size: 11px;
    color: #92400e;
    display: flex;
    gap: 8px;
    align-items: flex-start;
    line-height: 1.5;
}

/* Input */
.input-section { margin-bottom: 14px; }
.input-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.payment-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 800;
    color: #003366;
    outline: none;
    text-align: center;
    background: #f8fafc;
    cursor: default;
}

/* Upload */
.upload-area {
    width: 100%;
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    background: #f8fafc;
    transition: border-color 0.2s, background 0.2s;
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.upload-area:hover { border-color: #003366; background: #f0f4f8; }
.upload-area.has-file { border-color: #16a34a; border-style: solid; background: #f0fdf4; padding: 10px; }
.upload-placeholder { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.upload-placeholder i { font-size: 24px; color: #94a3b8; }
.upload-placeholder span { font-size: 12.5px; font-weight: 600; color: #64748b; }
.upload-placeholder small { font-size: 10px; color: #94a3b8; }
.receipt-preview { max-width: 100%; max-height: 160px; border-radius: 6px; object-fit: contain; }
.upload-hint { font-size: 10.5px; color: #16a34a; margin-top: 5px; font-weight: 600; }
.upload-hint.error { color: #ef4444; }

/* Button */
.btn-confirm {
    width: 100%;
    background: #003366;
    color: #fff;
    border: none;
    padding: 13px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    transition: background 0.2s, transform 0.15s;
}
.btn-confirm:hover { background: #002244; transform: translateY(-1px); }
.btn-confirm:active { transform: translateY(0); }
.btn-confirm:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

.cancel-link {
    display: block;
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
    text-decoration: none;
    margin-top: 10px;
    font-weight: 600;
    transition: color 0.15s;
}
.cancel-link:hover { color: #ef4444; }

@media (max-width: 700px) {
    .receipt-card { padding: 24px 20px; }
    .pay-grid { grid-template-columns: 1fr; }
    .pay-col-left { padding-right: 0; }
    .pay-col-right { padding-left: 0; border-left: none; margin-top: 20px; }
}
</style>
</head>
<body class="payment-body">
<div class="receipt-card">

    {{-- Header --}}
    <div class="receipt-header">
        <h2>Secure Payment</h2>
        <p>Ragadio Plaza Hotel &mdash; Reservation Deposit</p>
    </div>

    <div class="pay-grid">
        {{-- LEFT: Booking summary --}}
        <div class="pay-col-left">
            <div class="receipt-total-hero">
                <span class="hero-label">50% Deposit Due Now</span>
                <div class="amount">&#8369;{{ number_format($amount, 2) }}</div>
                <span class="hero-sub">Non-refundable &middot; Balance settled at lobby</span>
            </div>

            <div class="receipt-details">
                <div class="receipt-row">
                    <span class="label"><i class="fa-solid fa-bed"></i> Room Type</span>
                    <span class="value">{{ $room_type }}</span>
                </div>
                <div class="receipt-row">
                    <span class="label"><i class="fa-solid fa-moon"></i> Nights</span>
                    <span class="value">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
                </div>
                <div class="receipt-row">
                    <span class="label"><i class="fa-solid fa-calendar-check"></i> Check-in</span>
                    <span class="value">{{ \Carbon\Carbon::parse($check_in)->format('M d, Y') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="label"><i class="fa-solid fa-calendar-xmark"></i> Check-out</span>
                    <span class="value">{{ \Carbon\Carbon::parse($check_out)->format('M d, Y') }}</span>
                </div>
                <div class="receipt-row">
                    <span class="label"><i class="fa-solid fa-credit-card"></i> Payment Method</span>
                    <span class="value">{{ $payment_method }}</span>
                </div>
            </div>

            @if(!empty($selected_services) && count($selected_services) > 0)
            <div class="services-card">
                <span class="services-title">Add-On Services</span>
                @foreach($selected_services as $svc)
                <div class="service-line">
                    <span>{{ $svc->Service_Name }}</span>
                    <span class="price">+&#8369;{{ number_format($svc->Price, 2) }}</span>
                </div>
                @endforeach
                <div class="service-line subtotal">
                    <span>Services Subtotal</span>
                    <span class="price">+&#8369;{{ number_format($services_total ?? 0, 2) }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Payment action --}}
        <div class="pay-col-right">
            <div class="notice-box">
                <span>⚠️</span>
                <div><strong>Non-refundable deposit.</strong> The remaining balance will be collected upon arrival at the lobby.</div>
            </div>

            <form action="/book-final-submit" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="room_id"        value="{{ $room_id }}">
                <input type="hidden" name="check_in"       value="{{ $check_in }}">
                <input type="hidden" name="check_out"      value="{{ $check_out }}">
                <input type="hidden" name="amount"         value="{{ $amount }}">
                <input type="hidden" name="payment_method" value="{{ $payment_method }}">
                <input type="hidden" name="guests"         value="{{ request('guests') }}">
                <input type="hidden" name="service_ids"    value="{{ implode(',', array_map(fn($s) => is_object($s) ? $s->SERVICES_ID : $s, $service_ids ?? [])) }}">
                <input type="hidden" name="amount_paid"    value="{{ $amount }}">

                <div class="input-section">
                    <label class="input-label">Amount to Pay (&#8369;)</label>
                    <input type="text" class="payment-input" value="&#8369;{{ number_format($amount, 2) }}" readonly tabindex="-1">
                </div>

                {{-- Receipt Upload --}}
                <div class="input-section">
                    <label class="input-label">Upload Payment Receipt</label>
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('receiptFile').click()">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Click to upload receipt</span>
                            <small>JPG, PNG, PDF up to 5MB</small>
                        </div>
                        <img id="receiptPreview" class="receipt-preview" src="" alt="Receipt preview" style="display:none;">
                    </div>
                    <input type="file" id="receiptFile" name="receipt_image" accept="image/*,.pdf" style="display:none;" required>
                    <p class="upload-hint" id="uploadHint"></p>
                </div>

                <button type="submit" class="btn-confirm" id="confirmBtn">Confirm &amp; Pay Deposit</button>
            </form>

            <a href="/book/{{ $room_id }}" class="cancel-link">&#8592; Cancel and return</a>
        </div>
    </div>

</div>

<script>
const fileInput    = document.getElementById('receiptFile');
const uploadArea   = document.getElementById('uploadArea');
const placeholder  = document.getElementById('uploadPlaceholder');
const preview      = document.getElementById('receiptPreview');
const hint         = document.getElementById('uploadHint');

const MAX_MB = 5;

fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    if (file.size > MAX_MB * 1024 * 1024) {
        hint.textContent = 'File too large. Max 5MB.';
        hint.className = 'upload-hint error';
        return;
    }

    hint.textContent = '✓ ' + file.name;
    hint.className = 'upload-hint';
    uploadArea.classList.add('has-file');

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        placeholder.innerHTML = '<i class="fa-solid fa-file-pdf" style="font-size:32px;color:#003366"></i><span style="color:#003366">' + file.name + '</span>';
        placeholder.style.display = 'flex';
        preview.style.display = 'none';
    }
});

uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.style.borderColor = '#003366'; });
uploadArea.addEventListener('dragleave', () => { uploadArea.style.borderColor = ''; });
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.style.borderColor = '';
    const dt = e.dataTransfer;
    if (dt.files.length) {
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change'));
    }
});
</script>

</body>
</html>