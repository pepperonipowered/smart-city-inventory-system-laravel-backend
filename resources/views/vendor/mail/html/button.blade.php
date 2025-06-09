@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" 
   class="button button-{{ $color }}" 
   target="_blank" 
   rel="noopener"
   style="
        display: inline-block;
        background-color: #207A3C;
        border-radius: 8px;
        border: 1px solid #207A3C;
        border-bottom: 3px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: #ffffff;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        margin: 0;
        padding: 12px 24px;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        -webkit-text-size-adjust: none;
        width: auto;
        white-space: nowrap;
   ">
   {{ $slot }}
</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
