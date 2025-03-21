<div class="{{ $f['formGroupClass'] }}" id="uspdev-forms-disciplina-usp">

  <label for="{{ $f['id'] }}" class="form-label">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <select id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}">
    <option value="">Selecione uma disciplina..</option>
    @foreach ($disciplinas as $disciplina)
      <option value="{{ $disciplina['coddis'] }}" 
        @if (isset($formSubmission) && $formSubmission->data[$f['field']['name']] == $disciplina['coddis']) 
          selected 
        @endif>
        {{ $disciplina['nomdis'] }}
      </option>
    @endforeach
  </select>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {

    let attemptsDisc = 1;
    const maxAttemptsDisc = 50; // Tenta por 5 segundos (50 * 100ms)

    const intervalIdDisc = setInterval(() => {
      if (window.jQuery) {
        clearInterval(intervalIdDisc);
        console.log("Select2 carregou após " + attemptsDisc + " tentativas.");
        initSelect2Disc();
      } else if (attemptsDisc >= maxAttemptsDisc) {
        clearInterval(intervalIdDisc);
        console.error("jQuery não carregou após várias tentativas.");
      }
      attemptsDisc++;
    }, 100);

  });

  function initSelect2Disc() {
    var $oSelect2Disc = $('#{{ $f['id'] }}');

    $oSelect2Disc.select2({
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
