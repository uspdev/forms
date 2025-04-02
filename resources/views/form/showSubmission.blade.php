@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card">
      <div class="card-header card-header-sticky d-flex justify-content-between align-items-center">
        <h4>Submissão</h4>

        <div class="d-flex">

          <a href="{{ route('form.submissions.all', ['formDefinitionId' => $definition->id]) }}" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Voltar
          </a>

          <form
            action="{{ route('form.submissions.delete', ['formDefinitionId' => $definition->id, 'formSubmissionId' => $submission->id]) }}"
            method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
              onclick="return confirm('Tem certeza de que deseja excluir esta submissão?')">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </div>
      </div>
      <div class="card-body">

        {!! $formHtml !!}

      </div>
    </div>
  </div>
@endsection
