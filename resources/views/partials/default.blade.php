<div class="{{ $f['formGroupClass'] }}">
  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <input id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}"
    @if (isset($formSubmission) && isset($formSubmission->data[$f['field']['name']])) value="{{ $formSubmission->data[$f['field']['name']] }}" @endif
    {{ $f['required'] }}>
</div>
