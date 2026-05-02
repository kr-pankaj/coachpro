<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $fee->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid {{ $institute->brand_color ?? '#4f46e5' }};
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .institute-name {
            font-size: 24px;
            font-weight: bold;
            color: {{ $institute->brand_color ?? '#4f46e5' }};
            margin: 0 0 5px 0;
        }
        .institute-contact {
            font-size: 12px;
            color: #666;
            margin: 0;
            line-height: 1.5;
        }
        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 30px;
            background: #f9fafb;
            padding: 10px;
            border-radius: 5px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th, .details-table td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .details-table th {
            background-color: #f9fafb;
            width: 35%;
            font-weight: bold;
            color: #4b5563;
        }
        .amount-row th, .amount-row td {
            background-color: {{ $institute->brand_color ?? '#4f46e5' }}11;
            font-size: 16px;
        }
        .amount-value {
            font-weight: bold;
            color: {{ $institute->brand_color ?? '#4f46e5' }};
            font-size: 18px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
        .signatures {
            margin-top: 60px;
            width: 100%;
        }
        .sig-box {
            width: 40%;
            float: right;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .clear { clear: both; }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="header">
        @if($institute->logo_url)
            <img src="{{ $institute->logo_url }}" class="logo" alt="Logo">
        @endif
        <h1 class="institute-name">{{ $institute->name }}</h1>
        <p class="institute-contact">
            @if($institute->address){{ $institute->address }}<br>@endif
            @if($institute->city || $institute->state){{ $institute->city }}, {{ $institute->state }} {{ $institute->pincode }}<br>@endif
            @if($institute->phone)Phone: {{ $institute->phone }} | @endif
            @if($institute->contact_email)Email: {{ $institute->contact_email }}@endif
        </p>
    </div>

    <div class="receipt-title">Official Fee Receipt</div>

    <table class="details-table">
        <tr>
            <th>Receipt Number</th>
            <td>RCPT-{{ str_pad($fee->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th>Payment Date</th>
            <td>{{ \Carbon\Carbon::parse($fee->payment_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Student Name</th>
            <td><strong>{{ $fee->student->name }}</strong></td>
        </tr>
        <tr>
            <th>Batch / Class</th>
            <td>{{ $fee->student->batch->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Fee For Month</th>
            <td>{{ \Carbon\Carbon::parse($fee->month_year . '-01')->format('F Y') }}</td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td><span class="status-badge">{{ $fee->status }}</span></td>
        </tr>
        <tr class="amount-row">
            <th>Total Course Fee</th>
            <td style="font-weight: bold;">Rs. {{ number_format($fee->total_amount, 2) }}</td>
        </tr>
        @if($fee->discount_amount > 0)
        <tr>
            <th>Discount Given</th>
            <td style="color: #dc2626;">- Rs. {{ number_format($fee->discount_amount, 2) }}</td>
        </tr>
        @endif
        <tr>
            <th>Net Payable</th>
            <td style="font-weight: bold;">Rs. {{ number_format($fee->total_amount - $fee->discount_amount, 2) }}</td>
        </tr>
        <tr class="amount-row">
            <th>Total Amount Paid</th>
            <td class="amount-value">Rs. {{ number_format($fee->paid_amount, 2) }}</td>
        </tr>
        @if($fee->due_amount > 0)
        <tr>
            <th style="color: #dc2626;">Balance Remaining</th>
            <td style="color: #dc2626; font-weight: bold;">Rs. {{ number_format($fee->due_amount, 2) }}</td>
        </tr>
        @endif
    </table>

    @if(count($fee->payments) > 0)
    <h3 style="font-size: 14px; text-transform: uppercase; margin-bottom: 10px; color: #4b5563;">Payment History (Installments)</h3>
    <table class="details-table" style="font-size: 12px;">
        <thead>
            <tr>
                <th style="width: 25%;">Date</th>
                <th style="width: 25%;">Method</th>
                <th style="width: 25%;">Amount</th>
                <th style="width: 25%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fee->payments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td><strong>Rs. {{ number_format($payment->amount, 2) }}</strong></td>
                <td>{{ $payment->remarks ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="signatures">
        <div class="sig-box">
            Authorized Signatory<br>
            <strong>{{ $institute->name }}</strong>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        This is a computer-generated receipt and does not require a physical signature.<br>
        Thank you for your payment!
    </div>

</body>
</html>
