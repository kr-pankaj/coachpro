<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice_{{ $payment->razorpay_payment_id }}</title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #111827; margin: 0; padding: 0; line-height: 1.5; background: #f3f4f6; }
        .invoice-card { background: white; width: 210mm; min-height: 297mm; margin: 20px auto; padding: 40px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); border-radius: 20px; position: relative; box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .logo-section h1 { margin: 0; font-size: 24px; font-weight: 900; color: #4f46e5; letter-spacing: -0.05em; }
        .invoice-details { text-align: right; }
        .invoice-details h2 { margin: 0; font-size: 24px; font-weight: 900; color: #111827; text-transform: uppercase; letter-spacing: 0.1em; }
        .meta-grid { display: grid; grid-cols: 2; gap: 40px; margin-bottom: 60px; }
        .meta-item h4 { margin: 0 0 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.1em; }
        .meta-item p { margin: 0; font-size: 14px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { text-align: left; padding: 16px; background: #f9fafb; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; border-bottom: 2px solid #e5e7eb; }
        td { padding: 24px 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .total-section { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 12px 0; }
        .total-row.grand-total { border-top: 2px solid #4f46e5; margin-top: 12px; padding-top: 20px; font-size: 24px; font-weight: 900; color: #4f46e5; }
        .footer { margin-top: 80px; padding-top: 40px; border-top: 1px solid #f3f4f6; text-align: center; color: #9ca3af; font-size: 12px; }
        .print-btn { position: fixed; top: 20px; right: 20px; padding: 12px 24px; background: #4f46e5; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }
        @media print { .print-btn { display: none; } body { background: white; } .invoice-card { box-shadow: none; margin: 0; width: 100%; max-width: 100%; border-radius: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>

    <div class="invoice-card">
        <div class="header">
            <div class="logo-section">
                <h1>QuonixAI Elite</h1>
                <p style="font-size: 12px; color: #6b7280; font-weight: 600;">The Ultimate Command Center</p>
            </div>
            <div class="invoice-details">
                <h2>Invoice</h2>
                <p style="font-size: 12px; font-weight: 700; color: #4f46e5;">#INV-{{ $payment->id }}-{{ date('Ymd') }}</p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 60px;">
            <div class="meta-item">
                <h4>Billed To</h4>
                <p style="font-size: 18px; font-weight: 800;">{{ $payment->institute->name }}</p>
                <p style="color: #6b7280; font-weight: 500;">{{ $payment->institute->contact_email }}</p>
                <p style="color: #6b7280; font-weight: 500;">{{ $payment->institute->phone }}</p>
            </div>
            <div class="meta-item" style="text-align: right;">
                <h4>Payment Info</h4>
                <p>Transaction: <span style="color: #4f46e5;">{{ $payment->razorpay_payment_id }}</span></p>
                <p>Date: {{ $payment->created_at->format('M d, Y') }}</p>
                <p>Status: <span style="color: #059669;">SUCCESSFUL</span></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Duration</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p style="font-weight: 700; margin: 0;">{{ $payment->plan_name }} Subscription</p>
                        <p style="font-size: 11px; color: #9ca3af; margin: 4px 0 0;">Platform-wide access including all management tools and student CRM.</p>
                    </td>
                    <td style="text-align: center; font-weight: 700;">{{ $payment->months }} Month(s)</td>
                    <td style="text-align: right; font-weight: 800;">₹{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Subtotal</span>
                <span style="font-weight: 700;">₹{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="total-row">
                <span style="font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Tax (0%)</span>
                <span style="font-weight: 700;">₹0.00</span>
            </div>
            <div class="total-row grand-total">
                <span>Total</span>
                <span>₹{{ number_format($payment->amount, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 60px; padding: 24px; background: #fefce8; border-radius: 16px; border: 1px solid #fef08a;">
            <p style="margin: 0; font-size: 12px; color: #854d0e; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Subscription Note</p>
            <p style="margin: 0; font-size: 13px; color: #a16207; font-weight: 600;">This payment extends your institute's full access until <strong>{{ $payment->expires_at->format('M d, Y') }}</strong>.</p>
        </div>

        <div class="footer">
            <p style="font-weight: 700; margin-bottom: 4px; color: #4b5563;">Thank you for being part of QuonixAI Elite.</p>
            <p>This is a system-generated invoice and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>
