@props(['url'])
<tr>
<td align="center" class="px-6 pb-6 text-center">
<a href="{{ $url }}" class="inline-block text-center no-underline">
<img src="{{ asset('images/appkonkos.png') }}" alt="{{ config('app.name', 'APPKONKOS') }}" class="mx-auto h-16 w-16 rounded-full border border-blue-100 bg-white object-contain" width="64" height="64">
<span class="mt-4 block text-2xl font-extrabold text-slate-950">{{ trim($slot) ?: 'APPKONKOS' }}</span>
<span class="mt-1 block text-sm font-medium text-slate-500">Kos dan Kontrakan Terpercaya</span>
</a>
</td>
</tr>
