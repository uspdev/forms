<div class="{{ $field['formGroupClass'] }}">

  <label for="{{ $field['id'] }}">
    {{ $field['label'] }} {!! $field['requiredLabel'] !!}
  </label>
  <input type="file" id="{{ $field['id'] }}" name="file[{{ $field['name'] }}]" class="{{ $field['controlClass'] }}"
    @if (!empty($field['accept'])) accept="{{ $field['accept'] }}" @endif
    @if (!empty($field['required']) && $field['required']) required @endif>

</div>
