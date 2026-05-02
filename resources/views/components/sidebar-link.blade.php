@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-200 translate-x-1'
            : 'group flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-white hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-xl transition-all duration-200 hover:translate-x-1';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <div class="flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
            <svg class="w-5 h-5 {{ ($active ?? false) ? 'text-white' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="{{ $icon }}" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    @endif
    <span>{{ $slot }}</span>
</a>
