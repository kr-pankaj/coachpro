<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #ffffff;
            color: #333333;
            margin: 0;
            padding: 0;
            width: 240px;
            height: 380px;
        }
        .card {
            width: 240px;
            height: 380px;
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }
        .header {
            background-color: #444444;
            height: 180px;
            text-align: center;
            padding-top: 20px;
            position: relative;
            z-index: 1;
        }
        .logo-text {
            font-size: 11px;
            font-weight: bold;
            color: #06b6d4;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .photo-container {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            border-radius: 50%;
            border: 3px solid #06b6d4;
            overflow: hidden;
            background: #eeeeee;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name-section {
            margin-top: 10px;
            color: #ffffff;
        }
        .student-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .student-batch {
            font-size: 9px;
            opacity: 0.8;
            margin-top: 2px;
        }
        .wave-container {
            position: absolute;
            top: 150px;
            left: 0;
            width: 100%;
            z-index: 2;
        }
        .content {
            padding: 45px 20px 20px;
            font-size: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label {
            color: #666666;
            width: 60px;
            font-weight: normal;
        }
        .separator {
            width: 10px;
            color: #999999;
        }
        .value {
            color: #333333;
            font-weight: bold;
        }
        .accent-label {
            color: #06b6d4;
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
        }
        .qr-img {
            width: 35px;
            height: 35px;
            border: 1px solid #eeeeee;
            padding: 2px;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header Section -->
        <div class="header">
            <div class="logo-text">{{ $student->institute->name }}</div>
            <div class="photo-container">
                @if($student->photo_url)
                    <img src="{{ public_path($student->photo_url) }}" class="photo-img">
                @else
                    <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" class="photo-img" style="opacity: 0.6;">
                @endif
            </div>
            <div class="name-section">
                <div class="student-name">{{ $student->name }}</div>
                <div class="student-batch">{{ $student->batch->name ?? 'GENERAL BATCH' }}</div>
            </div>
        </div>

        <!-- Wave Decoration (SVG approach for PDF compatibility) -->
        <div class="wave-container">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 60px; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #ffffff;"></path>
                <path d="M0.00,49.98 C149.99,150.00 271.49,-49.98 500.00,49.98 L500.00,0.00 L0.00,0.00 Z" style="stroke: none; fill: #444444;"></path>
                <path d="M0.00,49.98 C149.99,150.00 300.00,-49.98 500.00,49.98" style="stroke: #06b6d4; stroke-width: 4px; fill: none; opacity: 0.5;"></path>
            </svg>
        </div>

        <!-- Body Section -->
        <div class="content">
            <table class="info-table">
                <tr>
                    <td class="label">ID</td>
                    <td class="separator">:</td>
                    <td class="value">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $student->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="separator">:</td>
                    <td class="value" style="font-size: 8px;">{{ $student->user->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="accent-label">Join</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $student->joined_date ? \Carbon\Carbon::parse($student->joined_date)->format('d-m-Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="accent-label">Status</td>
                    <td class="separator">:</td>
                    <td class="value" style="color: #059669;">ACTIVE</td>
                </tr>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <img class="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('dashboard')) }}">
            <div style="font-size: 6px; color: #999999; margin-top: 4px; letter-spacing: 1px;">VERIFIED STUDENT ID</div>
        </div>
    </div>
</body>
</html>
