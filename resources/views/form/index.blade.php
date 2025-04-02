@extends('layouts.app')

@section('content')
  <div class="container mt-4">
  <h2 class="mb-4">Definições de formulário</h2>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('form-definitions.create') }}" class="btn btn-primary btn-lg">Nova Definição</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="table-light">
        <tr>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($formDefinitions as $formDefinition)
          <tr>
            <td><a
                href="{{ route('form.submissions.all', ['formDefinitionId' => $formDefinition->id]) }}">{{ $formDefinition->name }}</a>
            </td>
            <td>{{ $formDefinition->description }}</td>
            <td class="d-flex justify-content-start">
              <a href="{{ route('form-definitions.edit', $formDefinition->id) }}"
                class="btn btn-warning btn-sm mr-2">Editar</a>

              <form action="{{ route('form-definitions.destroy', $formDefinition->id) }}" method="POST"
                style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                  onclick="return confirm('Tem certeza que deseja excluir esta definição?')">Excluir</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
