<div class="{{ $field['formGroupClass'] }}">
  
  <label for="{{ $field['id'] }}">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>

  <input type="time" id="{{ $field['id'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}"
    value="{{ $field['old'] }}" @required($field['required'])>
    
</div>
