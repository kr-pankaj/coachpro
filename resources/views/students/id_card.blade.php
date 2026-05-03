<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
            size: 243pt 371pt;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            width: 243pt;
            height: 371pt;
            background: #ffffff;
            overflow: hidden;
        }

        /* ── HEADER ── */
        .header {
            background: #2d3748;
            width: 100%;
            padding: 18pt 0 20pt;
            text-align: center;
        }
        .institute-name {
            font-size: 8pt;
            font-weight: bold;
            color: #81e6d9;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
        }
        .id-title {
            font-size: 6pt;
            color: #a0aec0;
            margin-top: 2pt;
            text-transform: uppercase;
            letter-spacing: 1pt;
        }

        /* ── PHOTO ── */
        .photo-wrap {
            text-align: center;
            margin-top: 12pt;
        }
        .photo-ring {
            display: inline-block;
            width: 76pt;
            height: 76pt;
            border-radius: 50%;
            border: 2.5pt solid #38b2ac;
            padding: 3pt;
            background: #2d3748;
        }
        .photo-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #4a5568;
        }
        .photo-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── NAME ── */
        .name-block {
            text-align: center;
            background: #2d3748;
            padding-bottom: 14pt;
        }
        .student-name {
            font-size: 14pt;
            font-weight: bold;
            color: #ffffff;
            margin-top: 8pt;
            text-transform: uppercase;
        }
        .batch-name {
            font-size: 7pt;
            color: #a0aec0;
            margin-top: 3pt;
        }

        /* ── DIVIDER STRIPE ── */
        .stripe {
            height: 4pt;
            background: linear-gradient(to right, #38b2ac, #2c7a7b);
        }

        /* ── INFO BODY ── */
        .info-body {
            background: #f7fafc;
            padding: 14pt 20pt;
        }
        .info-row {
            display: table;
            width: 100%;
            padding: 4pt 0;
            border-bottom: 0.5pt solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-key {
            display: table-cell;
            font-size: 8pt;
            color: #718096;
            width: 55pt;
            vertical-align: middle;
        }
        .info-colon {
            display: table-cell;
            font-size: 8pt;
            color: #cbd5e0;
            width: 10pt;
            vertical-align: middle;
        }
        .info-val {
            display: table-cell;
            font-size: 8pt;
            font-weight: bold;
            color: #2d3748;
            vertical-align: middle;
        }
        .info-val.accent {
            color: #2c7a7b;
        }

        /* ── QR FOOTER ── */
        .qr-footer {
            background: #2d3748;
            text-align: center;
            padding: 10pt 0 8pt;
        }
        .qr-box {
            display: inline-block;
            background: #ffffff;
            padding: 3pt;
            border-radius: 3pt;
        }
        .qr-box img {
            width: 36pt;
            height: 36pt;
            display: block;
        }
        .qr-label {
            font-size: 5.5pt;
            color: #81e6d9;
            margin-top: 5pt;
            letter-spacing: 0.8pt;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="institute-name">{{ $student->institute->name }}</div>
        <div class="id-title">Student Identity Card</div>
    </div>

    <!-- Photo -->
    <div class="photo-wrap" style="background:#2d3748;">
        <div class="photo-ring">
            <div class="photo-inner">
                @if($student->photo_url)
                    <img src="{{ public_path($student->photo_url) }}">
                @else
                    <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y">
                @endif
            </div>
        </div>
    </div>

    <!-- Name Block -->
    <div class="name-block">
        <div class="student-name">{{ $student->name }}</div>
        <div class="batch-name">{{ $student->batch->name ?? 'General Batch' }}</div>
    </div>

    <!-- Teal Stripe Divider -->
    <div class="stripe"></div>

    <!-- Info Rows -->
    <div class="info-body">
        <div class="info-row">
            <span class="info-key">Student ID</span>
            <span class="info-colon">:</span>
            <span class="info-val">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="info-key">Phone</span>
            <span class="info-colon">:</span>
            <span class="info-val">{{ $student->phone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-key">Email</span>
            <span class="info-colon">:</span>
            <span class="info-val" style="font-size:7pt;">{{ $student->user->email ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-key">Joined</span>
            <span class="info-colon">:</span>
            <span class="info-val accent">{{ $student->joined_date ? \Carbon\Carbon::parse($student->joined_date)->format('d-m-Y') : 'N/A' }}</span>
        </div>
    </div>

    <!-- QR Footer -->
    <div class="qr-footer">
        <div class="qr-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('dashboard')) }}">
        </div>
        <div class="qr-label">Scan to Verify</div>
    </div>
</body>
</html>
