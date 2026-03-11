<div class="{{ $field['formGroupClass'] }}" id="uspdev-forms-patrimonio-usp">
  <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>
  <select id="{{ $field['id'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}" @required($field['required'])>
    <option value="">Digite um número de patrimônio...</option>
    @if (isset($formSubmission) && isset($formSubmission->data[$field['name']]))
      @php
        $patrimonio = \Uspdev\Replicado\Bempatrimoniado::dump($formSubmission->data[$field['name']]);
      @endphp
      <option value="{{ $formSubmission->data[$field['name']] }}" selected>
        {{ $formSubmission->data[$field['name']] }}
        - {{ $patrimonio['epfmarpat'] }} - {{ $patrimonio['tippat'] }} - {{ $patrimonio['modpat'] }}
      </option>
    @elseif ($field['old'])
      @php
        $patrimonio = \Uspdev\Replicado\Bempatrimoniado::dump($field['old']);
      @endphp
      <option value="{{ $field['old'] }}" selected>
        {{ $field['old'] }}
        - {{ $patrimonio['epfmarpat'] }} - {{ $patrimonio['tippat'] }} -{{ $patrimonio['modpat'] }}
      </option>
    @endif
  </select>
</div>

<script>
  // Função auto-invocada para inicializar o Select2 com verificação de jQuery
  (function() {
    function scheduleInitPatr() {
      let attemptsPatr = 1;
      const maxAttemptsPatr = 50; // Tenta por 5 segundos (50 * 100ms)

      const intervalIdPatr = setInterval(() => {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
          clearInterval(intervalIdPatr);
          initSelect2Patr();
        } else if (attemptsPatr >= maxAttemptsPatr) {
          clearInterval(intervalIdPatr);
          console.error("jQuery não carregou após várias tentativas.");
        }
        attemptsPatr++;
      }, 100);
    }
    // Inicializa o Select2 quando o DOM estiver pronto ou quando um modal for aberto
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', scheduleInitPatr);
    } else {
      scheduleInitPatr();
    }
  })();

  function initSelect2Patr() {
    var $oSelect2Patr = $('#{{ $field['id'] }}');

    // Define o dropdownParent para garantir que o Select2 funcione corretamente dentro de modais
    var $modalParentPatr = $oSelect2Patr.closest('.modal');

    $oSelect2Patr.select2({
      ajax: {
        url: '{{ route('form.find.patrimonio') }}',
        dataType: 'json',
        delay: 1000,
        processResults: function(data) {
          if (data.results.original.results) {
            return {
              results: data.results.original.results
            };
          }
          return data;
        }
      },
      allowClear: true,
      placeholder: 'Digite um número de patrimônio...',
      minimumInputLength: 9,
      theme: 'bootstrap4',
      width: 'resolve',
      language: 'pt-BR',
      // Garante que o dropdown seja anexado ao modal correto, ou ao body se não estiver em um modal
      dropdownParent: $modalParentPatr.length ? $modalParentPatr : $(document.body)
    });

    // Coloca o foco no campo de busca ao abrir o Select2
    $oSelect2Patr.off('select2:open').on('select2:open', function() {
      var searchField = document.querySelector('.select2-container--open .select2-search__field');
      if (searchField) {
        searchField.focus();
      }
    });
  }
</script>
