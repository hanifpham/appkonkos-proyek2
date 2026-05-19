<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name', 'APPKONKOS') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
{!! $head ?? '' !!}
</head>
<body class="m-0 min-h-screen bg-blue-50 p-0 font-sans text-slate-700 antialiased">
<table class="min-h-screen w-full bg-blue-50 px-4 py-10" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center" class="align-top">
<table class="w-full max-w-2xl" width="640" cellpadding="0" cellspacing="0" role="presentation">
{!! $header ?? '' !!}

<tr>
<td class="rounded-3xl border border-blue-100 bg-white px-8 py-9 shadow-2xl shadow-slate-900/10">
<div class="prose prose-slate max-w-none prose-a:font-semibold prose-a:text-primary prose-h1:text-2xl prose-h1:font-bold prose-h1:text-slate-950 prose-p:leading-7 prose-p:text-slate-600">
{!! Illuminate\Mail\Markdown::parse($slot) !!}
</div>

{!! $subcopy ?? '' !!}
</td>
</tr>

{!! $footer ?? '' !!}
</table>
</td>
</tr>
</table>
</body>
</html>
