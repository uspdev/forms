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
  @include('uspdev-forms::partials.scripts.select2-usp-helper')

  window.uspdevFormsSelect2.initOnLoad({
    selector: '#{{ $field['id'] }}',
    url: '{{ route('form.find.patrimonio') }}',
    placeholder: 'Digite um número de patrimônio...',
    minimumInputLength: 9,
    processResults: function(data) {
      if (data.results && data.results.original && data.results.original.results) {
        return {
          results: data.results.original.results
        };
      }
      return data;
    }
  });
</script>
