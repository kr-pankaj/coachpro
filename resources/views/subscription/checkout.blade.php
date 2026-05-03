<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Payment — QuonixAI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; margin: 0; }
    </style>
</head>
<body>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;">
    <div style="background:white;border-radius:1.5rem;box-shadow:0 20px 60px rgba(0,0,0,0.1);max-width:480px;width:100%;overflow:hidden;">

        <!-- Header -->
        <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:2rem;text-align:center;">
            <div style="width:4rem;height:4rem;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h1 style="color:white;font-size:1.5rem;font-weight:800;margin:0 0 0.5rem;">Complete Your Payment</h1>
            <p style="color:rgba(255,255,255,0.8);font-size:0.9rem;margin:0;">Secure payment powered by Razorpay</p>
        </div>

        <!-- Order Summary -->
        <div style="padding:1.75rem 2rem;">
            <div style="background:#f8fafc;border-radius:1rem;padding:1.25rem;margin-bottom:1.5rem;border:1px solid #e2e8f0;">
                <p style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin:0 0 0.75rem;">Order Summary</p>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                    <span style="font-size:0.9rem;color:#374151;">QuonixAI — One-time Activation</span>
                    <span style="font-size:1rem;font-weight:700;color:#0f172a;">₹{{ number_format($amount / 100, 0) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:0.8rem;color:#64748b;">Institute</span>
                    <span style="font-size:0.8rem;color:#374151;font-weight:500;">{{ $institute->name }}</span>
                </div>
                <div style="height:1px;background:#e2e8f0;margin:0.75rem 0;"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:0.85rem;font-weight:600;color:#374151;">Total</span>
                    <span style="font-size:1.25rem;font-weight:800;color:#4f46e5;">₹{{ number_format($amount / 100, 0) }}</span>
                </div>
            </div>

            <!-- Features included -->
            <div style="margin-bottom:1.75rem;">
                <p style="font-size:0.8rem;font-weight:600;color:#374151;margin:0 0 0.75rem;">What's included:</p>
                @foreach(['Full access to all features', 'Unlimited students & batches', 'Attendance & fee management', 'Student self-registration portal', 'Priority support'] as $feature)
                <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.5rem;">
                    <svg width="14" height="14" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span style="font-size:0.85rem;color:#4b5563;">{{ $feature }}</span>
                </div>
                @endforeach
            </div>

            <!-- Pay Button -->
            <button id="pay-btn" onclick="openRazorpay()"
                style="width:100%;padding:1rem;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;font-weight:700;font-size:1rem;border:none;border-radius:0.75rem;cursor:pointer;transition:all 0.2s;letter-spacing:0.01em;display:flex;align-items:center;justify-content:center;gap:0.5rem;"
                onmouseover="this.style.opacity='0.92';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.opacity='1';this.style.transform='translateY(0)'">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M3 10h18M7 15h4" stroke-linecap="round"/></svg>
                Pay ₹{{ number_format($amount / 100, 0) }} Securely
            </button>

            <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-top:1rem;">
                <svg width="12" height="12" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round"/></svg>
                <span style="font-size:0.75rem;color:#94a3b8;">256-bit SSL encrypted · Secured by Razorpay</span>
            </div>

            <!-- Test credentials hint -->
            <div style="background:#fef9c3;border:1px solid #fcd34d;border-radius:0.75rem;padding:0.875rem 1rem;margin-top:1rem;">
                <p style="font-size:0.75rem;font-weight:700;color:#92400e;margin:0 0 0.5rem;">🧪 Test Mode — Use these credentials</p>
                <table style="font-size:0.75rem;color:#78350f;border-collapse:collapse;width:100%;">
                    <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;white-space:nowrap;font-weight:600;">Card No.</td><td><strong>4718 6008 1099 0683</strong></td></tr>
                    <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;font-weight:600;">Expiry</td><td>Any future date (e.g. 12/26)</td></tr>
                    <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;font-weight:600;">CVV</td><td><strong>100</strong></td></tr>
                    <tr><td style="padding:0.15rem 0.75rem 0.15rem 0;font-weight:600;">OTP</td><td><strong>1234</strong></td></tr>
                </table>
                <p style="font-size:0.72rem;color:#92400e;margin:0.5rem 0 0;">💡 Or pick <strong>UPI</strong> and enter <code>success@razorpay</code> — instant success!</p>
            </div>

            <div style="text-align:center;margin-top:1.25rem;">
                <a href="{{ route('subscription.index') }}" style="font-size:0.8rem;color:#64748b;text-decoration:none;">← Go back</a>
            </div>
        </div>
    </div>
</div>


<!-- No pre-existing form needed; we create it dynamically in the handler -->

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function openRazorpay() {
        var btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.innerHTML = 'Opening Razorpay...';

        var options = {
            key:         '{{ $key_id }}',
            amount:      {{ $amount }},
            currency:    '{{ $currency }}',
            name:        'QuonixAI',
            description: 'One-time Activation — {{ addslashes($institute->name) }}',
            image:       'https://ui-avatars.com/api/?name=CP&color=7F9CF5&background=4f46e5&size=128&bold=true',
            order_id:    '{{ $order_id }}',
            prefill: {
                name:  '{{ addslashes($user->name) }}',
                email: '{{ $user->email }}',
            },
            theme: { color: '#4f46e5' },

            handler: function(response) {
                // Show processing state
                btn.innerHTML = 'Verifying payment...';

                // Dynamically build and submit the verification form
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("subscription.verify") }}';

                var fields = {
                    '_token':                 '{{ csrf_token() }}',
                    'razorpay_order_id':      response.razorpay_order_id,
                    'razorpay_payment_id':    response.razorpay_payment_id,
                    'razorpay_signature':     response.razorpay_signature
                };

                Object.keys(fields).forEach(function(key) {
                    var input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = key;
                    input.value = fields[key] || '';
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            },

            modal: {
                ondismiss: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M3 10h18M7 15h4" stroke-linecap="round"/></svg> Pay ₹{{ number_format($amount / 100, 0) }} Securely';
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>
</body>
</html>

