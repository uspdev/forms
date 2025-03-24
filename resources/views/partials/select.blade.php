<div class="{{ $f['formGroupClass'] }}">

  <label for="{{ $f['id'] }}">{{ $f['field']['label'] }} {!! $f['requiredLabel'] !!}</label>

  <select id="{{ $f['id'] }}" name="{{ $f['field']['name'] }}" class="{{ $f['controlClass'] }}" @required($f['required'])>

    <option selected disabled hidden value="">Selecione um ..</option>
    @foreach ($f['field']['options'] as $o)
      <option value="{{ $o }}" @selected($f['old'] == $o)>
        {{ $o }}
      </option>
    @endforeach
  </select>
</div>
