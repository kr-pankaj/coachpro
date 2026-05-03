<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
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
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid #1e1b4b;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            height: 120px;
            text-align: center;
            padding-top: 20px;
            position: relative;
        }
        .logo-text {
            font-size: 18px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .photo-container {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 20px;
            margin: -50px auto 0;
            position: relative;
            z-index: 10;
            border: 4px solid #050514;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .initial {
            font-size: 40px;
            font-weight: 900;
            color: #4f46e5;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .student-name {
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 5px;
            color: #fff;
        }
        .student-batch {
            font-size: 10px;
            font-weight: 700;
            color: #818cf8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .info-grid {
            margin-top: 20px;
            text-align: left;
            font-size: 9px;
            padding: 0 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .label {
            color: #4b5563;
            font-weight: 900;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .value {
            color: #d1d5db;
            font-weight: 700;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background: #0f172a;
            border-top: 1px solid #1e293b;
            padding: 10px;
            text-align: center;
        }
        .qr-container {
            width: 40px;
            height: 40px;
            background: white;
            padding: 2px;
            border-radius: 5px;
            display: inline-block;
        }
        .qr-img {
            width: 100%;
            height: 100%;
        }
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background: linear-gradient(to top right, transparent 50%, #4f46e5 50%);
            opacity: 0.1;
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
                <div class="initial">{{ substr($student->name, 0, 1) }}</div>
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
