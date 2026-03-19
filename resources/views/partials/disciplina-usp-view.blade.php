@php
  $coddis = $submission['data'][$field['name']] ?? 'n/a';
  
  if ($longName ?? false) {
    // Modo longo: código + nome da disciplina
    $nomeDisciplina = \Uspdev\Replicado\Graduacao::nomeDisciplina($coddis) ?? 'n/a';
    $display = $coddis . ' - ' . $nomeDisciplina;
  } else {
    // Modo curto: apenas o código (comportamento padrão)
    $display = $coddis;
  }
  
  $title = $coddis . ' - ' . (\Uspdev\Replicado\Graduacao::nomeDisciplina($coddis) ?? 'n/a');
@endphp

<span title="{{ $title }}">
  {{ $display }}
</span>
