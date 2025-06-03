@php
  $coddis = $submission['data'][$field['name']] ?? 'n/a';
  $display = $coddis . ' - ' . (\Uspdev\Replicado\Graduacao::nomeDisciplina($coddis) ?? 'n/a');
@endphp

<span title="{{ $display }}">
  {{ Illuminate\Support\Str::limit($display, 100) }}
</span>
