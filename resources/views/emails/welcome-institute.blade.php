@component('mail::message')
# Welcome to QuonixAI, {{ $adminName }}! 🎉

Your institute **{{ $instituteName }}** has been successfully registered on QuonixAI. Your **14-day free trial** has started — no credit card required.

@component('mail::panel')
You have **14 days** to explore all features completely free. After that, you can subscribe to continue managing your institute effortlessly.
@endcomponent

Here's what you can do right now:

- ✅ **Add students** and organize them into batches
- ✅ **Mark attendance** daily with one tap
- ✅ **Record fees** and generate PDF receipts
- ✅ **Track leads** from walk-in enquiries
- ✅ **Create online tests** with auto-grading

@component('mail::button', ['url' => config('app.url') . '/dashboard', 'color' => 'blue'])
Go to Your Dashboard
@endcomponent

If you have any questions, just reply to this email. We're here to help!

Thanks,
**The QuonixAI Team**
@endcomponent
