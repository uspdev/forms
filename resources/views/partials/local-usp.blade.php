<div class="{{ $field['formGroupClass'] }}" id="uspdev-forms-localusp">
  <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>
  <select id="{{ $field['id'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}" @required($field['required'])>
    <option value="">Digite um número de local...</option>
    @if (isset($formSubmission) && isset($formSubmission->data[$field['name']]))
      @php
        $local = \Uspdev\Replicado\Estrutura::procurarLocal($formSubmission->data[$field['name']]);
      @endphp
      <option value="{{ $formSubmission->data[$field['name']] }}" selected>
        {{ $formSubmission->data[$field['name']] }} - {{ $local[0]['epflgr'] }}
        , {{ $local[0]['numlgr'] }} ({{ $local[0]['sglund'] }})
        - Bloco: {{ $local[0]['idfblc'] }}
        - Andar: {{ $local[0]['idfadr'] }} - {{ $local[0]['idfloc'] }}
      </option>
    @elseif ($field['old'])
      @php
        $local = \Uspdev\Replicado\Estrutura::procurarLocal($field['old']);
      @endphp
      <option value="{{ $field['old'] }}" selected>
        {{ $field['old'] }} - {{ $local[0]['epflgr'] }}
        , {{ $local[0]['numlgr'] }} ({{ $local[0]['sglund'] }})
        - Bloco: {{ $local[0]['idfblc'] }}
        - Andar: {{ $local[0]['idfadr'] }} - {{ $local[0]['idfloc'] }}
      </option>
    @endif
  </select>
</div>

<script>
  // Função auto-invocada para inicializar o Select2 com verificação de jQuery
  (function() {
    function scheduleInitLocal() {
      let attemptsLocal = 1;
      const maxAttemptsLocal = 50; // Tenta por 5 segundos (50 * 100ms)

      const intervalIdLocal = setInterval(() => {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
          clearInterval(intervalIdLocal);
          initSelect2Local();
        } else if (attemptsLocal >= maxAttemptsLocal) {
          clearInterval(intervalIdLocal);
          console.error("jQuery não carregou após várias tentativas.");
        }
        attemptsLocal++;
      }, 100);
    }
    // Inicializa o Select2 quando o DOM estiver pronto ou quando um modal for aberto
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', scheduleInitLocal);
    } else {
      scheduleInitLocal();
    }
  })();

  function initSelect2Local() {
    var $oSelect2Local = $('#{{ $field['id'] }}');

    // Define o dropdownParent para garantir que o Select2 funcione corretamente dentro de modais
    var $modalParentLocal = $oSelect2Local.closest('.modal');

    $oSelect2Local.select2({
      ajax: {
        url: '{{ route('form.find.local') }}',
        dataType: 'json',
        delay: 1000,
      },
      allowClear: true,
      placeholder: 'Digite um número de local...',
      minimumInputLength: 3,
      theme: 'bootstrap4',
      width: 'resolve',
      language: 'pt-BR',
      dropdownParent: $modalParentLocal.length ? $modalParentLocal : $(document.body)
    });

    // Coloca o foco no campo de busca ao abrir o Select2
    $oSelect2Local.off('select2:open').on('select2:open', function() {
      var searchField = document.querySelector('.select2-container--open .select2-search__field');
      if (searchField) {
        searchField.focus();
      }
    });
  }
</script>
