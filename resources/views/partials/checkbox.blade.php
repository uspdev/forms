<div class="{{ $f['formGroupClass'] }}">

  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  @foreach ($f['field']['options'] as $option)
    <div class="checkbox" style="display: inline-block; margin-right: 10px;">
      <input type="checkbox" name="{{ $f['field']['name'] }}[]" value="{{ $option['value'] }}"
        class="{{ $f['controlClass'] }}" 
        @if (isset($formSubmission) && in_array($option['value'], $formSubmission->data[$f['field']['name']])) checked @endif
        {{ $f['required'] }}>
      {{ $option['label'] }}
    </div>
  @endforeach

</div>
