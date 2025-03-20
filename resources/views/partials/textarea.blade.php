<div class="{{ $f['formGroupClass'] }}">

  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <textarea id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}" {{ $f['required'] }}>
  @if (isset($formSubmission) && isset($formSubmission->data[$f['field']['name']])) {{ $formSubmission->data[$f['field']['name']] }} @endif
  </textarea>

</div>
