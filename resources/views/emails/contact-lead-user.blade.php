<!DOCTYPE html>
<html>
<body style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;">
<div style="max-width:540px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
  <div style="background:linear-gradient(135deg,#ec4899,#f59e0b);padding:36px;text-align:center;">
    <h1 style="margin:0;color:#fff;font-size:24px;font-weight:800;">Thanks for reaching out! 🙌</h1>
    <p style="color:rgba(255,255,255,0.85);margin:10px 0 0;font-size:14px;">QuonixAI – Coaching Management Platform</p>
  </div>
  <div style="padding:36px;">
    <p style="color:#374151;font-size:15px;line-height:1.7;">Hi <strong>{{ $lead->name }}</strong>,</p>
    <p style="color:#374151;font-size:15px;line-height:1.7;">
      We've received your enquiry and our team will get back to you within <strong>24 hours</strong>. 
      We're excited to show you how QuonixAI can transform your institute!
    </p>

    <div style="background:#fdf4ff;border-left:4px solid #ec4899;border-radius:8px;padding:16px 20px;margin:24px 0;">
      <p style="margin:0;font-size:13px;font-weight:700;color:#9d174d;text-transform:uppercase;letter-spacing:0.05em;">Your Enquiry Summary</p>
      <p style="margin:8px 0 0;font-size:14px;color:#374151;"><strong>Institute:</strong> {{ $lead->institute_name ?? 'N/A' }}</p>
      <p style="margin:4px 0 0;font-size:14px;color:#374151;"><strong>Plan Interest:</strong> {{ ucfirst(str_replace('_',' ',$lead->plan_interest ?? 'Not specified')) }}</p>
      @if($lead->message)
      <p style="margin:4px 0 0;font-size:14px;color:#374151;"><strong>Message:</strong> {{ $lead->message }}</p>
      @endif
    </div>

    <p style="color:#6b7280;font-size:14px;line-height:1.7;">
      In the meantime, feel free to explore what QuonixAI offers:
    </p>
    <ul style="color:#374151;font-size:14px;line-height:2;padding-left:20px;">
      <li>✅ Smart Student & Batch Management</li>
      <li>✅ Automated Fee Tracking & PDF Receipts</li>
      <li>✅ Attendance with Parent Notifications</li>
      <li>✅ Online Quizzes with Auto-Grading</li>
      <li>✅ Your Own Branded Portal URL</li>
    </ul>

    <div style="text-align:center;margin-top:32px;">
      <a href="{{ config('app.url') }}/register" style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#ec4899,#f59e0b);color:#fff;font-weight:800;font-size:15px;border-radius:12px;text-decoration:none;">Start Your 14-Day Free Trial</a>
    </div>

    <p style="color:#9ca3af;font-size:13px;text-align:center;margin-top:24px;">
      Just reply to this email if you have any questions. We are happy to help!
    </p>
  </div>
  <div style="background:#f9fafb;padding:16px;text-align:center;font-size:12px;color:#9ca3af;">
    © {{ date('Y') }} QuonixAI SaaS. All rights reserved.
  </div>
</div>
</body>
</html>
