<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { 
            margin: 0px;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #050514;
            color: white;
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
            background-color: #050514;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            height: 100px;
            text-align: center;
            padding-top: 20px;
            position: relative;
        }
        .logo-text {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 10px;
        }
        .photo-container {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 15px;
            margin: -45px auto 0;
            position: relative;
            z-index: 10;
            border: 3px solid #050514;
            overflow: hidden;
        }
        .initial {
            width: 100%;
            height: 100%;
            display: block;
            text-align: center;
            line-height: 90px;
            font-size: 40px;
            font-weight: 900;
            color: #4f46e5;
        }
        .content {
            padding: 15px;
            text-align: center;
        }
        .student-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #fff;
        }
        .student-batch {
            font-size: 9px;
            font-weight: bold;
            color: #818cf8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .info-grid {
            margin-top: 10px;
            text-align: left;
            font-size: 9px;
            padding: 0 15px;
        }
        .info-item {
            margin-bottom: 6px;
        }
        .label {
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
            display: block;
        }
        .value {
            color: #e5e7eb;
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 80px;
            background: #0f172a;
            border-top: 1px solid #1e293b;
            text-align: center;
            padding-top: 10px;
        }
        .qr-container {
            width: 45px;
            height: 45px;
            background: white;
            padding: 3px;
            border-radius: 6px;
            margin: 0 auto;
        }
        .qr-img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="logo-text">{{ $student->institute->name }}</div>
            <div style="font-size: 8px; font-weight: bold; opacity: 0.7; margin-top: 5px;">STUDENT IDENTITY CARD</div>
        </div>
        
        <div class="photo-container">
            @if($student->photo_url)
                <img src="{{ public_path($student->photo_url) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{-- Professional Dummy Profile Image --}}
                <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;">
            @endif
        </div>

        <div class="content">
            <div class="student-name">{{ $student->name }}</div>
            <div class="student-batch">{{ $student->batch->name ?? 'GENERAL BATCH' }}</div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Student ID</span>
                    <span class="value">#{{ str_pad($student->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Joined Date</span>
                    <span class="value">{{ $student->joined_date ? \Carbon\Carbon::parse($student->joined_date)->format('d M, Y') : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Contact</span>
                    <span class="value">{{ $student->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="qr-container">
                {{-- Using a dummy QR for now, in real case would be student's unique profile link --}}
                <img class="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('dashboard')) }}">
            </div>
            <div style="font-size: 7px; color: #4b5563; margin-top: 5px; font-weight: bold;">VERIFY IDENTITY</div>
        </div>

        <div class="wave"></div>
    </div>
</body>
</html>
