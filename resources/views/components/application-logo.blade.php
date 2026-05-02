@props(['light' => false])

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <svg viewBox="0 0 400 100" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
        <!-- Icon Part -->
        <g transform="translate(10, 10) scale(0.8)">
            <!-- Stylized C -->
            <path d="M45 10 C 20 10, 5 30, 5 50 C 5 70, 20 90, 45 90 L 60 90 L 60 75 L 45 75 C 30 75, 20 65, 20 50 C 20 35, 30 25, 45 25 L 60 25 L 60 10 Z" fill="{{ $light ? '#ffffff' : '#001f3f' }}" />
            <!-- Stylized P -->
            <path d="M55 10 L 55 90 L 70 90 L 70 55 L 90 55 C 110 55, 115 40, 115 32 C 115 25, 110 10, 90 10 Z M 70 25 L 90 25 C 100 25, 100 40, 90 40 L 70 40 Z" fill="{{ $light ? '#cbd5e1' : '#007bff' }}" />
            <!-- Small Person Icon inside C -->
            <circle cx="35" cy="45" r="5" fill="{{ $light ? '#ffffff' : '#007bff' }}" />
            <path d="M25 65 Q 35 55, 45 65" stroke="{{ $light ? '#ffffff' : '#007bff' }}" stroke-width="3" fill="none" />
        </g>
        
        <!-- Text Part -->
        <text x="120" y="55" font-family="ui-sans-serif, system-ui, -apple-system, sans-serif" font-weight="900" font-size="44" fill="{{ $light ? '#ffffff' : '#001f3f' }}">Coach</text>
        <text x="250" y="55" font-family="ui-sans-serif, system-ui, -apple-system, sans-serif" font-weight="900" font-size="44" fill="{{ $light ? '#ffffff' : '#007bff' }}">Pro</text>
        
        <!-- Tagline -->
        <text x="120" y="85" font-family="ui-sans-serif, system-ui, -apple-system, sans-serif" font-weight="700" font-size="10" fill="{{ $light ? 'rgba(255,255,255,0.7)' : '#64748b' }}" letter-spacing="2">COACHING. EMPOWERING. ACHIEVING.</text>
    </svg>
</div>
