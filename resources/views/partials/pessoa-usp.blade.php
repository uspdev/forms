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
  @include('uspdev-forms::partials.scripts.select2-usp-helper')

  window.uspdevFormsSelect2.initOnLoad({
    selector: '#{{ $field['id'] }}',
    url: '{{ route('form.find.pessoa') }}',
    placeholder: 'Digite o nome ou codpes..',
    minimumInputLength: 4,
  });
</script>
