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
            position: relative;
        }

        /* ── BACKGROUND ART ── */
        .bg-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 140pt;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            z-index: 1;
        }
        .bg-curve {
            position: absolute;
            top: 100pt;
            left: -50pt;
            width: 350pt;
            height: 100pt;
            background: #ffffff;
            border-radius: 50%;
            z-index: 2;
        }

        /* ── CONTENT WRAPPER ── */
        .content {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            display: block;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            padding-top: 15pt;
        }
        .institute-name {
            font-size: 10pt;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.5pt;
            margin-bottom: 2pt;
        }
        .id-title {
            font-size: 6.5pt;
            color: rgba(255, 255, 255, 0.85);
            text-transform: uppercase;
            letter-spacing: 1pt;
            font-weight: 500;
        }

        /* ── PHOTO ── */
        .photo-wrap {
            text-align: center;
            margin-top: 15pt;
        }
        .photo-ring {
            display: inline-block;
            width: 80pt;
            height: 80pt;
            border-radius: 50%;
            border: 3pt solid #ffffff;
            background: #ffffff;
            /* Box shadows don't always render in dompdf, but we keep the thick white border for the floating effect */
        }
        .photo-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #f1f5f9;
        }
        .photo-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── NAME BLOCK ── */
        .name-block {
            text-align: center;
            margin-top: 10pt;
            padding: 0 15pt;
        }
        .student-name {
            font-size: 14pt;
            font-weight: 800;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }
        .batch-name {
            font-size: 7.5pt;
            color: #6366f1;
            font-weight: 600;
            margin-top: 3pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
        }

        /* ── INFO BODY ── */
        .info-body {
            margin-top: 18pt;
            padding: 0 25pt;
        }
        .info-row {
            display: table;
            width: 100%;
            padding: 5pt 0;
            border-bottom: 0.5pt solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-key {
            display: table-cell;
            font-size: 7.5pt;
            color: #64748b;
            width: 50pt;
            vertical-align: middle;
            font-weight: 500;
            text-transform: uppercase;
        }
        .info-colon {
            display: table-cell;
            font-size: 7.5pt;
            color: #cbd5e0;
            width: 10pt;
            vertical-align: middle;
        }
        .info-val {
            display: table-cell;
            font-size: 8pt;
            font-weight: 700;
            color: #1e293b;
            vertical-align: middle;
        }
        .info-val.accent {
            color: #4f46e5;
        }

        /* ── FOOTER ── */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 25pt;
            background: #f8fafc;
            border-top: 1pt solid #e2e8f0;
            text-align: center;
            line-height: 25pt;
            z-index: 10;
        }
        .footer-text {
            font-size: 6pt;
            color: #94a3b8;
            letter-spacing: 0.5pt;
        }
        
        /* ── DECORATIVE ACCENT ── */
        .accent-bar {
            position: absolute;
            bottom: 25pt;
            left: 0;
            width: 100%;
            height: 3pt;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Background Elements -->
    <div class="bg-top"></div>
    <div class="bg-curve"></div>

    <div class="content">
        <!-- Header -->
        <div class="header">
            <div class="institute-name">{{ $student->institute->name }}</div>
            <div class="id-title">Student Identity Card</div>
        </div>

        <!-- Photo -->
        <div class="photo-wrap">
            <div class="photo-ring">
                <div class="photo-inner">
                    @if($student->photo_url)
                        <img src="{{ public_path($student->photo_url) }}">
                    @else
                        <img src="{{ public_path('images/default-avatar.png') }}" onerror="this.src='https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'">
                    @endif
                </div>
            </div>
        </div>

        <!-- Name Block -->
        <div class="name-block">
            <div class="student-name">{{ $student->name }}</div>
            <div class="batch-name">
                @if($student->batches->count() > 0)
                    {{ $student->batches->pluck('name')->implode(' • ') }}
                @else
                    {{ $student->batch->name ?? 'General Batch' }}
                @endif
            </div>
        </div>

        <!-- Info Rows -->
        <div class="info-body">
            <div class="info-row">
                <span class="info-key">ID No</span>
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
                <span class="info-val accent">{{ $student->joined_date ? \Carbon\Carbon::parse($student->joined_date)->format('d M Y') : 'N/A' }}</span>
            </div>
        </div>
    </div>
    
    <div class="accent-bar"></div>
    <div class="footer">
        <span class="footer-text">Property of {{ $student->institute->name }}</span>
    </div>
</body>
</html>
