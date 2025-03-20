<form action="{{ $form->action }}" method="{{ $form->method }}" name="{{ $form->definition->name }}">
  <input type="hidden" name="form_definition" value="{{ $form->definition->name }}">
  <input type="hidden" name="form_key" value="{{ $form->key }}">
  @csrf()

  {!! $fields !!}

  <button type="submit" class="btn btn-primary {{ $form->btnSize }}">{{ $form->btnLabel }}</button>
</form>
