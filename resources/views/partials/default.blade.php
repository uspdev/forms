<div class="{{ $field['formGroupClass'] }}">
  
  <label for="{{ $field['id'] }}">{{ $field['label'] }} {!! $field['requiredLabel'] !!}</label>

  <input id="{{ $field['id'] }}" type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="{{ $field['controlClass'] }}"
    value="{{  $field['value'] ?? $field['old'] }}"
    @if($field['type'] === 'text' && isset($field['maxlength'])) maxlength="{{ $field['maxlength'] }}" @endif
    @if($field['type'] === 'number' && isset($field['min'])) min="{{ $field['min'] }}" @endif
    @if($field['type'] === 'number' && isset($field['max'])) max="{{ $field['max'] }}" @endif
    @if($field['type'] === 'number' && isset($field['step'])) step="{{ $field['step'] }}" @endif
    @required($field['required'])>
    
</div>
