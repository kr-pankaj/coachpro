<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $student->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #1a202c;
            background-color: #fff;
        }
        .certificate-frame {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 60px;
            box-sizing: border-box;
            @if($template->background_image)
                background-image: url('{{ public_path('storage/' . $template->background_image) }}');
                background-size: 100% 100%;
            @else
                background-color: #ffffff;
            @endif
        }
        
        /* Minimal Corner Accents if no background */
        @if(!$template->background_image)
        .corner-tl { position: absolute; top: 40px; left: 40px; width: 100px; height: 100px; border-top: 2px solid #e2e8f0; border-left: 2px solid #e2e8f0; }
        .corner-br { position: absolute; bottom: 40px; right: 40px; width: 100px; height: 100px; border-bottom: 2px solid #e2e8f0; border-right: 2px solid #e2e8f0; }
        @endif

        .inner-content {
            height: 100%;
            border: 1px solid #f1f5f9;
            display: table;
            width: 100%;
            text-align: center;
        }

        .main-body {
            display: table-cell;
            vertical-align: middle;
            padding: 0 100px;
        }

        .header-section {
            margin-bottom: 40px;
        }

        .logo {
            max-height: 50px;
            margin-bottom: 20px;
            filter: grayscale(1); /* Minimalist feel */
            opacity: 0.8;
        }

        .title-top {
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5em;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .main-title {
            font-size: 48px;
            font-weight: 300;
            color: #1e293b;
            margin: 0;
            font-family: 'Times New Roman', serif;
            letter-spacing: -1px;
        }

        .presented-to {
            font-size: 14px;
            font-style: italic;
            color: #64748b;
            margin: 30px 0;
        }

        .student-name {
            font-size: 56px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 30px;
            font-family: 'Times New Roman', serif;
            border-bottom: 1px solid #e2e8f0;
            display: inline-block;
            padding: 0 40px 10px 40px;
        }

        .description {
            font-size: 18px;
            line-height: 1.8;
            color: #475569;
            max-width: 750px;
            margin: 0 auto;
        }

        .footer-section {
            margin-top: 60px;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 33%;
        }

        .sig-line {
            width: 180px;
            border-top: 1px solid #cbd5e1;
            margin: 10px auto;
        }

        .sig-img {
            max-height: 50px;
            margin-bottom: -10px;
            mix-blend-mode: multiply;
        }

        .sig-name {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .sig-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-top: 4px;
        }

        .certificate-id {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            color: #94a3b8;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="certificate-frame">
        @if(!$template->background_image)
            <div class="corner-tl"></div>
            <div class="corner-br"></div>
        @endif

        <div class="inner-content">
            <div class="main-body">
                <div class="header-section">
                    @if($institute->logo_url)
                        <img src="{{ public_path('storage/' . $institute->logo_url) }}" class="logo">
                    @endif
                    <div class="title-top">Certificate of Achievement</div>
                    <h1 class="main-title">{{ $template->title }}</h1>
                </div>

                <div class="presented-to">This certificate is proudly presented to</div>

                <div class="student-name">{{ $student->name }}</div>

                <div class="description">
                    {!! nl2br(e($body)) !!}
                </div>

                <div class="footer-section">
                    <table class="footer-table">
                        <tr>
                            <td class="signature-box">
                                <div class="sig-name">{{ $issuedCertificate->issued_at->format('M d, Y') }}</div>
                                <div class="sig-line"></div>
                                <div class="sig-title">Date of Issue</div>
                            </td>
                            <td class="signature-box">
                                {{-- Placeholder for a seal or empty space --}}
                                <div style="height: 60px;"></div>
                            </td>
                            <td class="signature-box">
                                @if($template->signature_image)
                                    <img src="{{ public_path('storage/' . $template->signature_image) }}" class="sig-img">
                                @endif
                                <div class="sig-line"></div>
                                <div class="sig-name">{{ $template->authorized_signatory_name }}</div>
                                <div class="sig-title">{{ $template->authorized_signatory_designation }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="certificate-id">
            Verification ID: {{ $issuedCertificate->certificate_number }} · Authenticated by {{ config('app.name') }}
        </div>
    </div>
</body>
</html>

