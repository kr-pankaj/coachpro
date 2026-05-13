@extends('layouts.static')

@section('title', 'Privacy Policy')

@section('content')
<div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
    <h1 class="text-4xl font-black text-gray-900 mb-8 tracking-tight">Privacy Policy</h1>
    
    <div class="prose prose-indigo max-w-none space-y-6 text-gray-600">
        <p class="text-lg leading-relaxed">At QuonixAI, we are committed to protecting your privacy and the data of your institute. This Privacy Policy explains how we collect, use, and safeguard your information.</p>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">1. Information We Collect</h2>
            <p>We collect information that you provide directly to us when you create an account, such as your name, email address, institute name, and payment information. We also store data related to your institute's operations, including student details, attendance records, and fee information.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">2. How We Use Your Information</h2>
            <p>We use the collected information to:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Provide and maintain the QuonixAI platform.</li>
                <li>Process payments and manage subscriptions.</li>
                <li>Communicate with you about platform updates and support.</li>
                <li>Improve our services based on usage patterns.</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">3. Data Ownership and Security</h2>
            <p>You retain full ownership of the data you upload to QuonixAI. We implement industry-standard security measures to protect your data from unauthorized access, loss, or alteration. All data is encrypted during transmission and at rest.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">4. Third-Party Services</h2>
            <p>We may use third-party services for specific functions like payment processing (e.g., Razorpay) and email delivery. These providers have their own privacy policies and only access the minimum data required to perform their tasks.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">5. Your Rights</h2>
            <p>You have the right to access, update, or delete your personal and institute data at any time through your dashboard. If you close your account, your data will be permanently deleted from our active databases.</p>
        </section>

        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-3">6. Updates to This Policy</h2>
            <p>We may update this Privacy Policy from time to time. We will notify you of any significant changes by posting the new policy on this page and updating the "Last Modified" date.</p>
        </section>

        <p class="pt-8 text-sm text-gray-400">Last Modified: May 13, 2026</p>
    </div>
</div>
@endsection
