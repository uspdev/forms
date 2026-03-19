@php
  $numpat = $submission['data'][$field['name']] ?? 'n/a';
  $codigoFormatado = str_pad(substr($numpat, 0, -6), 3, '0', STR_PAD_LEFT) . '.' . str_pad(substr($numpat, -6), 6, '0', STR_PAD_LEFT);
  $title = '';
  if ($bemPatrimoniado = \Uspdev\Replicado\Bempatrimoniado::dump($numpat)) {
      $title = $bemPatrimoniado['epfmarpat'] . ' - ' . $bemPatrimoniado['tippat'] . ' - ' . $bemPatrimoniado['modpat'];
  }

  if ($longName ?? false) {
      // Modo longo: código + descrição completa do patrimônio
      $display = $codigoFormatado . (!empty($title) ? ' - ' . $title : '');
  } else {
      // Modo curto: apenas o código (comportamento padrão)
      $display = $codigoFormatado;
  }
@endphp

<span title="{{ $title }}">
  {{ $display }}
</span>
