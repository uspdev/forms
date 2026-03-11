{{--  
  a rota senhaunicaFindUsers previsa ser ajustado com a permissão correta no config/senhaunica.php
  Masakik, em 20/3/2025 
--}}

<div class="{{ $field['formGroupClass'] }}" id="uspdev-forms-pessoa-usp">

  <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>

  <select id="{{ $field['id'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}" @required($field['required'])>
    <option value="">Digite o nome ou codpes..</option>
    @if (isset($formSubmission) && isset($formSubmission->data[$field['name']]))
      <option value="{{ $formSubmission->data[$field['name']] }}" selected>
        {{ $formSubmission->data[$field['name']] }}
        {{ \Uspdev\Replicado\Pessoa::retornarNome($formSubmission->data[$field['name']]) }}
      </option>
    @elseif ($field['old'])
      <option value="{{ $field['old'] }}" selected>
        {{ $field['old'] }} {{ \Uspdev\Replicado\Pessoa::retornarNome($field['old']) }}
      </option>
    @elseif(\Illuminate\Support\Facades\Auth::user())
      <option value="{{ \Illuminate\Support\Facades\Auth::user()->codpes }}" selected>
        {{ \Illuminate\Support\Facades\Auth::user()->codpes }}
        {{ \Uspdev\Replicado\Pessoa::retornarNome(\Illuminate\Support\Facades\Auth::user()->codpes) }}
      </option>
    @endif
  </select>
</div>

<script>
  // Função auto-invocada para inicializar o Select2 com verificação de jQuery
  (function() {
    function scheduleInitPessoa() {
      let attemptsPessoa = 1;
      const maxAttemptsPessoa = 50; // Tenta por 5 segundos (50 * 100ms)

      const intervalIdPessoa = setInterval(() => {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
          clearInterval(intervalIdPessoa);
          initSelect2Pessoa();
        } else if (attemptsPessoa >= maxAttemptsPessoa) {
          clearInterval(intervalIdPessoa);
          console.error("jQuery não carregou após várias tentativas.");
        }
        attemptsPessoa++;
      }, 100);
    }
    // Inicializa o Select2 quando o DOM estiver pronto ou quando um modal for aberto
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', scheduleInitPessoa);
    } else {
      scheduleInitPessoa();
    }
  })();

  function initSelect2Pessoa() {
    var $oSelect2Pessoa = $('#{{ $field['id'] }}');

    // Define o dropdownParent para garantir que o Select2 funcione corretamente dentro de modais
    var $modalParentPessoa = $oSelect2Pessoa.closest('.modal');

    $oSelect2Pessoa.select2({
      ajax: {
        url: '{{ route('form.find.pessoa') }}',
        dataType: 'json',
        delay: 1000
      },
      allowClear: true,
      placeholder: 'Digite o nome ou codpes..',
      minimumInputLength: 4,
      theme: 'bootstrap4',
      width: 'resolve',
      language: 'pt-BR',
      // Garante que o dropdown seja anexado ao modal correto, ou ao body se não estiver em um modal
      dropdownParent: $modalParentPessoa.length ? $modalParentPessoa : $(document.body)
    });

    // Coloca o foco no campo de busca ao abrir o Select2
    $oSelect2Pessoa.off('select2:open').on('select2:open', function() {
      var searchField = document.querySelector('.select2-container--open .select2-search__field');
      if (searchField) {
        searchField.focus();
      }
    });
  }
</script>
