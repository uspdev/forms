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
  @include('uspdev-forms::partials.scripts.select2-usp-helper')

  window.uspdevFormsSelect2.initOnLoad({
    selector: '#{{ $field['id'] }}',
    url: '{{ route('form.find.local') }}',
    placeholder: 'Digite um número de local...',
    minimumInputLength: 3,
  });
</script>
