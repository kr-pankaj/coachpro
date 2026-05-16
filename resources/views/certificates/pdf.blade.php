<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $student->name }}</title>
    <style>
        /* ── DomPDF-safe: no flex, no table, no height:100% ── */
        @page {
            margin: 0;
            size: 842px 595px;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 842px;
            height: 595px;
            overflow: hidden;
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            background: #ffffff;
            color: #1a202c;
        }

        /* ── Outer Frame ── */
        .page {
            position: absolute;
            top: 0;
            left: 0;
            width: 842px;
            height: 595px;
            overflow: hidden;
        }

        @if($template->background_image)
        .bg-image {
            position: absolute;
            top: 0; left: 0;
            width: 842px;
            height: 595px;
        }
        @else
        /* Decorative border lines when no bg image */
        .border-outer {
            position: absolute;
            top: 20px; left: 20px;
            width: 802px; height: 555px;
            border: 1.5px solid #e2e8f0;
        }
        .border-inner {
            position: absolute;
            top: 30px; left: 30px;
            width: 782px; height: 535px;
            border: 0.5px solid #f1f5f9;
        }
        /* Gold corner accents */
        .corner { position: absolute; width: 50px; height: 50px; }
        .corner-tl { top: 38px; left: 38px; border-top: 2px solid #c7b97a; border-left: 2px solid #c7b97a; }
        .corner-tr { top: 38px; right: 38px; border-top: 2px solid #c7b97a; border-right: 2px solid #c7b97a; }
        .corner-bl { bottom: 38px; left: 38px; border-bottom: 2px solid #c7b97a; border-left: 2px solid #c7b97a; }
        .corner-br { bottom: 38px; right: 38px; border-bottom: 2px solid #c7b97a; border-right: 2px solid #c7b97a; }
        @endif

        /* ── Header Section: top 0–160px ── */
        .header {
            position: absolute;
            top: 55px;
            left: 0;
            width: 842px;
            text-align: center;
        }
        .logo {
            max-height: 40px;
            max-width: 140px;
        }
        .label-top {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.35em;
            color: #94a3b8;
            margin-top: 10px;
        }
        .title-main {
            font-size: 32px;
            font-weight: normal;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-top: 6px;
            font-family: 'DejaVu Serif', 'Times New Roman', serif;
        }

        /* ── Gold Divider ── */
        .divider {
            position: absolute;
            top: 195px;
            left: 50%;
            margin-left: -60px;
            width: 120px;
            height: 1px;
            background: #c7b97a;
        }

        /* ── Recipient Section: ~210–350px ── */
        .presented-to {
            position: absolute;
            top: 210px;
            left: 0;
            width: 842px;
            text-align: center;
            font-size: 12px;
            font-style: italic;
            color: #64748b;
        }
        .student-name {
            position: absolute;
            top: 235px;
            left: 0;
            width: 842px;
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            color: #0f172a;
            font-family: 'DejaVu Serif', 'Times New Roman', serif;
            letter-spacing: -0.5px;
        }
        .name-underline {
            position: absolute;
            top: 296px;
            left: 50%;
            margin-left: -180px;
            width: 360px;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Body Text: 310–390px ── */
        .body-text {
            position: absolute;
            top: 308px;
            left: 80px;
            width: 682px;
            text-align: center;
            font-size: 12px;
            line-height: 1.7;
            color: #475569;
        }

        /* ── Footer / Signatures: 420–555px ── */
        .footer {
            position: absolute;
            top: 435px;
            left: 0;
            width: 842px;
        }

        /* Three-column signature layout using absolute within footer */
        .sig-col-left {
            position: absolute;
            left: 90px;
            width: 180px;
            text-align: center;
        }
        .sig-col-center {
            position: absolute;
            left: 50%;
            margin-left: -90px;
            width: 180px;
            text-align: center;
        }
        .sig-col-right {
            position: absolute;
            right: 90px;
            width: 180px;
            text-align: center;
        }

        .sig-img {
            max-height: 40px;
            max-width: 120px;
            margin-bottom: 4px;
        }
        .sig-line {
            width: 150px;
            height: 1px;
            background: #cbd5e1;
            margin: 8px auto 6px auto;
        }
        .sig-name {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }
        .sig-title {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* ── Certificate ID bar at the very bottom ── */
        .cert-id {
            position: absolute;
            top: 560px;
            left: 0;
            width: 842px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        /* Seal circle placeholder */
        .seal {
            width: 60px;
            height: 60px;
            border: 2px solid #e2e8f0;
            border-radius: 50%;
            margin: 0 auto;
            line-height: 56px;
            font-size: 9px;
            color: #cbd5e1;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
    </style>
</head>
<body>
<div class="page">

    @if($template->background_image)
        <img src="{{ public_path('storage/' . $template->background_image) }}" class="bg-image" alt="">
    @else
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>
    @endif

    {{-- Header --}}
    <div class="header">
        @if($institute->logo_url)
            <img src="{{ public_path('storage/' . $institute->logo_url) }}" class="logo" alt="{{ $institute->name }}"><br>
        @endif
        <div class="label-top">{{ $institute->name }} &nbsp;·&nbsp; Certificate of Achievement</div>
        <div class="title-main">{{ $template->title }}</div>
    </div>

    {{-- Gold divider --}}
    <div class="divider"></div>

    {{-- Recipient --}}
    <div class="presented-to">This certificate is proudly presented to</div>
    <div class="student-name">{{ $student->name }}</div>
    <div class="name-underline"></div>

    {{-- Body text --}}
    <div class="body-text">
        {!! nl2br(e($body)) !!}
    </div>

    {{-- Signatures footer --}}
    <div class="footer">
        {{-- Left: Date --}}
        <div class="sig-col-left">
            <div class="sig-name">{{ $issuedCertificate->issued_at->format('M d, Y') }}</div>
            <div class="sig-line"></div>
            <div class="sig-title">Date of Issue</div>
        </div>

        {{-- Center: Seal --}}
        <div class="sig-col-center">
            <div class="seal">SEAL</div>
        </div>

        {{-- Right: Signatory --}}
        <div class="sig-col-right">
            @if($template->signature_image)
                <img src="{{ public_path('storage/' . $template->signature_image) }}" class="sig-img" alt="Signature">
                <br>
            @endif
            <div class="sig-line"></div>
            <div class="sig-name">{{ $template->authorized_signatory_name }}</div>
            <div class="sig-title">{{ $template->authorized_signatory_designation }}</div>
        </div>
    </div>

    {{-- Certificate ID --}}
    <div class="cert-id">
        Verification ID: {{ $issuedCertificate->certificate_number }} &nbsp;·&nbsp; Authenticated by {{ config('app.name') }}
    </div>

</div>
</body>
</html>
