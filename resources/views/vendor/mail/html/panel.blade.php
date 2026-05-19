<table class="my-6 w-full rounded-2xl border-l-4 border-primary bg-blue-50" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="px-5 py-4 text-sm leading-6 text-slate-700">
<div class="prose prose-slate max-w-none prose-p:my-0 prose-p:text-sm prose-p:leading-6 prose-p:text-slate-700">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</div>
</td>
</tr>
</table>
