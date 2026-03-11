{{-- Gerador de campo para disciplina-usp --}}

<div class="{{ $field['formGroupClass'] }}" id="uspdev-forms-disciplina-usp">

    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>
    <select id="{{ $field['id'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}" @required($field['required'])>
        <option value="">Selecione uma disciplina...</option>
        @if (isset($formSubmission) && isset($formSubmission->data[$field['name']]))
            <option value="{{ $formSubmission->data[$field['name']] }}" selected>
                {{ $formSubmission->data[$field['name']] }}
                {{ \Uspdev\Replicado\Graduacao::nomeDisciplina($formSubmission->data[$field['name']]) }}</option>
        @elseif ($field['old'])
            <option value="{{ $field['old'] }}" selected>
                {{ $field['old'] }} {{ \Uspdev\Replicado\Graduacao::nomeDisciplina($field['old']) }}
            </option>
        @endif
    </select>

</div>

<script>
    // Função auto-invocada para inicializar o Select2 com verificação de jQuery
    (function() {
        function scheduleInitDisc() {
            let attemptsDisc = 1;
            const maxAttemptsDisc = 50; // Tenta por 5 segundos (50 * 100ms)

            const intervalIdDisc = setInterval(() => {
                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                    clearInterval(intervalIdDisc);
                    initSelect2Disc();
                } else if (attemptsDisc >= maxAttemptsDisc) {
                    clearInterval(intervalIdDisc);
                    console.error("jQuery não carregou após várias tentativas.");
                }
                attemptsDisc++;
            }, 100);
        }
        // Inicializa o Select2 quando o DOM estiver pronto ou quando um modal for aberto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', scheduleInitDisc);
        } else {
            scheduleInitDisc();
        }
    })();

    function initSelect2Disc() {
        var $oSelect2Disc = $('#{{ $field['id'] }}');

        // Define o dropdownParent para garantir que o Select2 funcione corretamente dentro de modais
        var $modalParentDisc = $oSelect2Disc.closest('.modal');

        $oSelect2Disc.select2({
            ajax: {
                url: '{{ route('form.find.disciplina') }}',
                dataType: 'json',
                delay: 1000
            },
            allowClear: true,
            placeholder: 'Selecione uma disciplina...',
            minimumInputLength: 3,
            theme: 'bootstrap4',
            width: 'resolve',
            language: 'pt-BR',
            // Garante que o dropdown seja anexado ao modal correto, ou ao body se não estiver em um modal
            dropdownParent: $modalParentDisc.length ? $modalParentDisc : $(document.body)
        });

        // Coloca o foco no campo de busca ao abrir o Select2
        $oSelect2Disc.off('select2:open').on('select2:open', function() {
            var searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        });
    }
</script>
