@php
    $initial = strtoupper(substr($name ?? 'U', 0, 1));
    $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#FF9F43', '#54A0FF'];
    $color = $colors[ord($initial) % count($colors)];
@endphp

<div class="default-avatar" style="
    width: {{ $size ?? 40 }}px;
    height: {{ $size ?? 40 }}px;
    border-radius: 50%;
    background: {{ $color }};
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: {{ ($size ?? 40) / 2 }}px;
    text-transform: uppercase;
">
    {{ $initial }}
</div>