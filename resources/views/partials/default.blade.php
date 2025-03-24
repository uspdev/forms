<div class="{{ $f['formGroupClass'] }}">
  
  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <input id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}"
    value="{{ $f['old'] }}" @required($f['required'])>
    
</div>
