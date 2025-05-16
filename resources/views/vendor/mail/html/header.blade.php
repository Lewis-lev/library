@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('favicon.ico') }}" class="logo" alt="MyLibrary Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
