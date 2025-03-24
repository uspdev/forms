<div class="{{ $f['formGroupClass'] }}">

  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <textarea id="{{ $f['id'] }}" 
    name="{{ $f['field']['name'] }}" 
    class="{{ $f['controlClass'] }}" 
    @required($f['required'])
    >{{ $f['old'] }}</textarea>

</div>
