@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
<div style="display: flex; align-items: center; justify-content: center;">
    <img src="{{ asset('img/logo.svg') }}" class="logo" alt="UpManager Logo" style="height: 40px; width: auto; margin-right: 10px;">
    <span style="font-size: 24px; font-weight: bold; color: #1f2937;">UPMANAGER</span>
</div>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
