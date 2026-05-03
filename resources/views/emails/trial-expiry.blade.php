@component('mail::message')
# Your Trial is Ending Soon ⏳

Hi there,

Your **QuonixAI** free trial for **{{ $instituteName }}** will expire in **{{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}**.

@component('mail::panel')
After your trial ends, you will lose access to the dashboard. Subscribe now to continue managing your students, attendance, fees, and more — without any interruption.
@endcomponent

Our subscription is just **₹1,999** — a one-time payment that keeps your institute running smoothly.

@component('mail::button', ['url' => config('app.url') . '/subscription', 'color' => 'blue'])
Subscribe Now & Continue
@endcomponent

Have questions? Just reply to this email.

Thanks,
**The QuonixAI Team**
@endcomponent
