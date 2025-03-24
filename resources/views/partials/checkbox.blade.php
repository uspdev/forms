<div class="{{ $f['formGroupClass'] }}">

  <div class="form-label">
    {{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}
  </div>
  
  @foreach ($f['field']['options'] as $option)
  
    <div class="form-check form-check-inline">
      <input 
        id="{{ $f['id'] }}-{{ $loop->iteration }}"
        type="checkbox" 
        name="{{ $f['field']['name'] }}[]" 
        value="{{ $option['value'] }}"
        class="form-check-input" 
        @checked(in_array($option['value'], (array) $f['old']))
        @required($f['required'])
      >
      
      <label class="form-check-label" for="{{ $f['id'] }}-{{ $loop->iteration }}">
        {{ $option['label'] }}
      </label>
    </div>
  @endforeach
</div>

