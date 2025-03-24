{{--  
  a rota senhaunicaFindUsers previsa ser ajustado com a permissão correta no config/senhaunica.php
  Masakik, em 20/3/2025 
--}}

<div class="{{ $f['formGroupClass'] }}" id="uspdev-forms-pessoa-usp">

  <label for="{{ $f['id'] }}" class="form-label">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <select id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}" @required($f['required'])>
    <option value="">Digite o nome ou codpes..</option>
    @if ($f['old'])
      <option value="{{ $f['old'] }}" selected>
        {{ $f['old'] }} {{ \Uspdev\Replicado\Pessoa::retornarNome($f['old']) }}
      </option>
    @endif
  </select>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    let attemptsPessoa = 1;
    const maxAttemptsPessoa = 50; // Tenta por 5 segundos (50 * 100ms)

    const intervalIdPessoa = setInterval(() => {
      if (window.jQuery) {
        clearInterval(intervalIdPessoa);
        console.log("Select carregou após " + attemptsPessoa + " tentativas.");
        initSelect2Pessoa();
      } else if (attemptsPessoa >= maxAttemptsPessoa) {
        clearInterval(intervalIdPessoa);
        console.error("jQuery não carregou após várias tentativas.");
      }
      attemptsPessoa++;
    }, 100);

  });

  function initSelect2Pessoa() {
    var $oSelect2Pessoa = $('#{{ $f['id'] }}');

    $oSelect2Pessoa.select2({
      ajax: {
        url: '{{ route('SenhaunicaFindUsers') }}',
        dataType: 'json',
        delay: 1000
      },
      minimumInputLength: 4,
      theme: 'bootstrap4',
      width: 'resolve',
      language: 'pt-BR'
    });

    // Coloca o foco no campo de busca ao abrir o Select2
    $(document).on('select2:open', () => {
      document.querySelector('.select2-search__field').focus();
    });
  }
</script>
