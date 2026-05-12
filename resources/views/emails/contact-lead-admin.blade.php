<!DOCTYPE html>
<html>
<body style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f3f4f6;margin:0;padding:0;">
<div style="max-width:560px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
  <div style="background:linear-gradient(135deg,#ec4899,#f59e0b);padding:32px;text-align:center;">
    <h1 style="margin:0;color:#fff;font-size:22px;font-weight:800;">🔔 New Lead Received</h1>
  </div>
  <div style="padding:32px;">
    <p style="color:#374151;font-size:15px;margin-bottom:24px;">A new contact enquiry has been submitted on the QuonixAI landing page.</p>

    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;width:40%;">Name</td>
        <td style="padding:12px 8px;color:#111827;font-weight:700;">{{ $lead->name }}</td>
      </tr>
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;">Email</td>
        <td style="padding:12px 8px;color:#111827;">{{ $lead->email }}</td>
      </tr>
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;">Phone</td>
        <td style="padding:12px 8px;color:#111827;">{{ $lead->phone ?? '—' }}</td>
      </tr>
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;">Institute</td>
        <td style="padding:12px 8px;color:#111827;">{{ $lead->institute_name ?? '—' }}</td>
      </tr>
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;">City</td>
        <td style="padding:12px 8px;color:#111827;">{{ $lead->city ?? '—' }}</td>
      </tr>
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;">Plan Interest</td>
        <td style="padding:12px 8px;color:#4f46e5;font-weight:700;">{{ ucfirst(str_replace('_', ' ', $lead->plan_interest ?? 'Not specified')) }}</td>
      </tr>
      <tr>
        <td style="padding:12px 8px;color:#6b7280;font-weight:600;vertical-align:top;">Message</td>
        <td style="padding:12px 8px;color:#374151;">{{ $lead->message ?? '—' }}</td>
      </tr>
    </table>

    <div style="margin-top:28px;text-align:center;">
      <a href="{{ config('app.url') }}/superadmin/contact-leads" style="display:inline-block;padding:12px 32px;background:linear-gradient(135deg,#ec4899,#f59e0b);color:#fff;font-weight:700;font-size:14px;border-radius:10px;text-decoration:none;">View All Leads</a>
    </div>
  </div>
  <div style="background:#f9fafb;padding:16px;text-align:center;font-size:12px;color:#9ca3af;">© {{ date('Y') }} QuonixAI SaaS</div>
</div>
</body>
</html>
