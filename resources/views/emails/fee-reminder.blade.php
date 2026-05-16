@component('mail::message')
# Fee Payment Reminder 💳

Dear **{{ $studentName }}**,

We hope you are enjoying your learning experience at **{{ $instituteName }}**. This is a courtesy reminder regarding your outstanding fee for **{{ $monthYear }}**.

@component('mail::panel')
### 💰 Outstanding Balance
**Amount Due: ₹{{ number_format($amount, 0) }}**
@endcomponent

Timely fee payments allow us to maintain high-quality education standards and facilities for all students. You can view your complete payment history and download receipts in your student portal.

@component('mail::button', ['url' => config('app.url'), 'color' => 'primary'])
Access Student Portal
@endcomponent

*If you have recently made this payment, please disregard this message or share the receipt with the administration office.*

Thank you for being a part of our institute!

Warm regards,  
**Finance Department**  
{{ $instituteName }}
@endcomponent
