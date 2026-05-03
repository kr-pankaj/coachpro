<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #020617;
            color: #f8fafc;
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
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
        }
        .header {
            height: 70px;
            text-align: center;
            padding-top: 25px;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .logo-text {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #818cf8;
        }
        .photo-section {
            margin-top: 25px;
            text-align: center;
        }
        .photo-container {
            width: 110px;
            height: 110px;
            margin: 0 auto;
            border-radius: 50%;
            border: 2px solid #4f46e5;
            padding: 3px;
            background: rgba(79, 70, 229, 0.1);
        }
        .photo-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #1e293b;
        }
        .content {
            padding: 20px 15px;
            text-align: center;
        }
        .student-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #fff;
        }
        .student-batch {
            font-size: 8px;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .info-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 12px;
            text-align: left;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .label {
            font-size: 6px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 1px;
        }
        .value {
            font-size: 10px;
            color: #cbd5e1;
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            text-align: center;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 8px;
        }
        .qr-container {
            width: 32px;
            height: 32px;
            background: white;
            padding: 2px;
            border-radius: 4px;
            margin: 0 auto;
        }
        .qr-img {
            width: 100%;
            height: 100%;
        }
        .verify-text {
            font-size: 6px;
            color: #475569;
            margin-top: 4px;
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="logo-text">{{ $student->institute->name }}</div>
        </div>
        
        <div class="photo-section">
            <div class="photo-container">
                <div class="photo-inner">
                    @if($student->photo_url)
                        <img src="{{ public_path($student->photo_url) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;">
                    @endif
                </div>
            </div>
        </div>

        <div class="content">
            <div class="student-name">{{ $student->name }}</div>
            <div class="student-batch">{{ $student->batch->name ?? 'GENERAL BATCH' }}</div>

            <div class="info-card">
                <div style="display: table; width: 100%;">
                    <div style="display: table-cell; width: 50%;">
                        <div class="label">ID NUMBER</div>
                        <div class="value">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div style="display: table-cell;">
                        <div class="label">SINCE</div>
                        <div class="value">{{ $student->joined_date ? \Carbon\Carbon::parse($student->joined_date)->format('M Y') : 'N/A' }}</div>
                    </div>
                </div>
                <div style="margin-top: 8px;">
                    <div class="label">CONTACT</div>
                    <div class="value">{{ $student->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="qr-container">
                <img class="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('dashboard')) }}">
            </div>
            <div class="verify-text">SECURE IDENTITY VERIFIED</div>
        </div>
    </div>
</body>
</html>
