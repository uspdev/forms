@php
  $display = $submission['data'][$field['name']] ?? 'n/a';
@endphp

<span title="{{ $display }}">
  {{ Illuminate\Support\Str::limit($display, 100) }}
</span>
