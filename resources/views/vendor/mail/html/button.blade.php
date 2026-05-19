@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
@php
    $buttonClass = match ($color) {
        'green', 'success' => 'bg-emerald-600 hover:bg-emerald-700',
        'red', 'error' => 'bg-red-600 hover:bg-red-700',
        default => 'bg-primary hover:bg-primary-dark',
    };
@endphp
<table class="my-8 w-full" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<a href="{{ $url }}" class="inline-block rounded-full px-7 py-3.5 text-sm font-bold text-white no-underline shadow-lg shadow-blue-900/20 {{ $buttonClass }}" target="_blank" rel="noopener">
{!! $slot !!}
</a>
</td>
</tr>
</table>
