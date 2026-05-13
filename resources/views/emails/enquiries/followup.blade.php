<x-mail::message>
{!! nl2br(e($customBody)) !!}

<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 2rem 0;">

<div style="text-align: center; color: #6b7280; font-size: 0.875rem;">
    @if($institute->logo)
        <img src="{{ Storage::url($institute->logo) }}" alt="{{ $institute->name }} Logo" style="max-height: 50px; margin-bottom: 1rem; border-radius: 8px;">
    @endif
    <h3 style="margin: 0; color: #111827; font-weight: 700;">{{ $institute->name }}</h3>
    @if($institute->address)
        <p style="margin: 0.25rem 0 0 0;">{{ $institute->address }}</p>
    @endif
    <p style="margin: 0.25rem 0 0 0;">
        @if($institute->phone)
            <a href="tel:{{ $institute->phone }}" style="color: #4f46e5; text-decoration: none;">{{ $institute->phone }}</a>
        @endif
        @if($institute->phone && $institute->email)
            | 
        @endif
        @if($institute->email)
            <a href="mailto:{{ $institute->email }}" style="color: #4f46e5; text-decoration: none;">{{ $institute->email }}</a>
        @endif
    </p>
</div>
</x-mail::message>
