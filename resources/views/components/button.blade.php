@props([
    'type' => 'button',
    'color' => 'primary',
    'icon' => null,
    'outline' => false,
    'rounded' => 'pill',
    'size' => '',
    'class' => '',
])

@php
    $btnClass = $outline ? "btn-outline-{$color}" : "btn-{$color}";
    $sizeClass = $size ? "btn-{$size}" : "";
    $roundedClass = $rounded ? "rounded-{$rounded}" : "";
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn $btnClass $sizeClass $roundedClass shadow-sm fw-bold hover-scale $class"]) }}>
    @if($icon)
        <i class="{{ $icon }} me-2"></i>
    @endif
    {{ $slot }}
</button>

<style>
.hover-scale { transition: transform 0.2s ease; }
.hover-scale:hover { transform: scale(1.05); }
</style>