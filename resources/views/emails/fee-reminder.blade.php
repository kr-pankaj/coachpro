@component('mail::message')
# Fee Payment Reminder 📋

Dear **{{ $studentName }}**,

This is a friendly reminder from **{{ $instituteName }}** that your fee for **{{ $monthYear }}** is pending.

@component('mail::panel')
**Amount Due: ₹{{ number_format($amount, 2) }}**

Please make the payment at the earliest to avoid any inconvenience.
@endcomponent

If you have already paid, please ignore this email or inform the institute admin.

Thanks,
**{{ $instituteName }}** via QuonixAI
@endcomponent
