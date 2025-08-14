@php
  $codlocalusp = $submission['data'][$field['name']] ?? 'n/a';
  $local = \Uspdev\Replicado\Estrutura::procurarLocal($codlocalusp)[0] ?? 'n/a';
  if(!empty($local) && is_array($local)) {
    $display = $codlocalusp . ' - ' . ($local['epflgr'] ?? 'n/a') . ', ' . ($local['numlgr'] ?? 'n/a') . ' (' . ($local['sglund'] ?? 'n/a') . ') - Bloco: ' . ($local['idfblc'] ?? 'n/a') . ' - Andar: ' . ($local['idfadr'] ?? 'n/a') . ' - ' . ($local['idfloc'] ?? 'n/a');
  } else {
    $display = $codlocalusp;
  }
@endphp

<span title="{{ $display }}">
  {{ Illuminate\Support\Str::limit($display, 100) }}
</span>