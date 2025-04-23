@extends('layouts.app')

@section('content')
  <a href="{{ route('form-definitions.index') }}" class="btn btn-secondary btn-sm mr-2">
    <i class="fas fa-arrow-left"></i> Voltar
  </a>
  <h2>Submissões para {{ $form->name }}</h2>
  <p>Aqui estão listadas todas as submissões feitas para esse formulário</p>
  @include('uspdev-forms::partials.submissions-table')
@endsection
