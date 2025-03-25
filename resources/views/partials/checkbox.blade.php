<div class="{{ $field['formGroupClass'] }}">

  <div class="form-label">
    {{ $field['label'] }} {!! $field['requiredLabel'] !!}
  </div>
  
  @foreach ($field['options'] as $option)
  
    <div class="form-check form-check-inline">
      <input 
        id="{{ $field['id'] }}-{{ $loop->iteration }}"
        type="checkbox" 
        name="{{ $field['name'] }}[]" 
        value="{{ $option['value'] }}"
        class="form-check-input" 
        @checked(in_array($option['value'], (array) $field['old']))
        @required($field['required'])
      >
      
      <label class="form-check-label" for="{{ $field['id'] }}-{{ $loop->iteration }}">
        {{ $option['label'] }}
      </label>
    </div>
  @endforeach
</div>

