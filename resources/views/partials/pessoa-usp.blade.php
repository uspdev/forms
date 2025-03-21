{{--  
  a rota senhaunicaFindUsers previsa ser ajustado com a permissão correta no config/senhaunica.php
  Masakik, em 20/3/2025 
--}}

<div class="{{ $f['formGroupClass'] }}" id="uspdev-forms-pessoa-usp">

  <label for="{{ $f['id'] }}" class="form-label">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <select id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}">
    @if (isset($formSubmission) && isset($formSubmission->data[$f['field']['name']]))
      <option value="{{ $formSubmission->data[$f['field']['name']] }}" selected>{{ $formSubmission->data[$f['field']['name']] }}</option>
    @else
      <option value="0">Digite o nome ou codpes..</option>
    @endif
  </select>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    let attempts = 1;
    const maxAttempts = 50; // Tenta por 5 segundos (50 * 100ms)

    const intervalId = setInterval(() => {
      if (window.jQuery) {
        clearInterval(intervalId);
        console.log("Select carregou após " + attempts + " tentativas.");
        initSelect2();
      } else if (attempts >= maxAttempts) {
        clearInterval(intervalId);
        console.error("jQuery não carregou após várias tentativas.");
      }
      attempts++;
    }, 100);

  });

  function initSelect2() {
    var $oSelect2 = $('#{{ $f['id'] }}');

    $oSelect2.select2({
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
