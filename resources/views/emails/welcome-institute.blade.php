@component('mail::message')
# Welcome to the QuonixAI Family, {{ $adminName }}! 🚀

We are thrilled to help you transform **{{ $instituteName }}** into a digital powerhouse. Your **14-day premium trial** is now active — giving you full access to every professional tool we offer.

@component('mail::panel')
### 💎 Premium Trial Active
Your trial ends on **{{ now()->addDays(14)->format('M d, Y') }}**. Use this time to explore the complete automation suite. No credit card required.
@endcomponent

### ⚡ Quick Start Guide
To get the most out of QuonixAI today, we recommend these three steps:

1. **Setup Your Branding:** Go to Settings to upload your logo and verify your institute details.
2. **Create Your First Batch:** Define your subjects and time slots in the Academic section.
3. **Onboard Students:** Add students manually or import them to start tracking attendance and fees.

@component('mail::button', ['url' => config('app.url') . '/' . $institute->slug . '/dashboard', 'color' => 'success'])
Launch Your Dashboard
@endcomponent

**Pro Tip:** Use the **Expense Manager** under Management to track your overheads and see real-time profitability analytics!

If you need a personalized demo or have any questions, simply reply to this email. Our team is ready to support your growth.

Best regards,  
**The QuonixAI Elite Team**
@endcomponent
