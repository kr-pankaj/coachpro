<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1f2937; line-height: 1.6; margin: 0; padding: 0; background-color: #f9fafb; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        .header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 40px; text-align: center; color: white; }
        .content { padding: 40px; }
        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; padding: 4px 12px; background: #ecfdf5; color: #059669; border-radius: 9999px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 16px; }
        .invoice-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin: 24px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .label { color: #6b7280; font-weight: 500; }
        .value { color: #111827; font-weight: 700; text-align: right; }
        .total { border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 12px; font-size: 18px; color: #4f46e5; }
        .btn { display: inline-block; padding: 12px 32px; background: #4f46e5; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em;">CoachPro Elite</h1>
            <p style="margin: 8px 0 0; opacity: 0.8; font-size: 14px;">Subscription Payment Confirmation</p>
        </div>
        <div class="content">
            <div class="badge">Payment Successful</div>
            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 800; color: #111827;">Thank you for your trust, {{ $instituteName }}!</h2>
            <p style="font-size: 15px; color: #4b5563;">We have received your payment for the <strong>{{ $planName }}</strong>. Your institute access has been successfully extended.</p>
            
            <div class="invoice-box">
                <div class="row">
                    <span class="label">Payment ID:</span>
                    <span class="value">{{ $paymentId }}</span>
                </div>
                <div class="row">
                    <span class="label">Date:</span>
                    <span class="value">{{ now()->format('M d, Y H:i') }}</span>
                </div>
                <div class="row">
                    <span class="label">Plan:</span>
                    <span class="value">{{ $planName }}</span>
                </div>
                <div class="row">
                    <span class="label">Validity Until:</span>
                    <span class="value" style="color: #059669;">{{ $expiresAt->format('M d, Y') }}</span>
                </div>
                <div class="row total">
                    <span class="label" style="color: #111827;">Amount Paid:</span>
                    <span class="value">₹{{ number_format($amountPaid, 2) }}</span>
                </div>
            </div>

            <p style="font-size: 14px; color: #6b7280;">You can now continue managing your students, attendance, and finances without any interruption.</p>
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/dashboard" class="btn">Go to Command Center</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CoachPro SaaS Platform. All rights reserved.<br>
            This is a computer-generated invoice.
        </div>
    </div>
</body>
</html>
