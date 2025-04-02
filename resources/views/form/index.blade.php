@extends('layouts.app')

@section('content')
  <div class="container mt-3">
    <h2>Definições</h2>
    <a href="{{ route('form-definitions.create') }}" class="btn btn-primary mb-3">Nova definição</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($formDefinitions as $formDefinition)
          <tr>
            <td><a href="{{ route('form.submissions.all', ['formDefinitionId' => $formDefinition->id]) }}">{{ $formDefinition->name }}</a></td>
            <td>{{ $formDefinition->description }}</td>
            <td>
              <a href="{{ route('form-definitions.edit', $formDefinition->id) }}" class="btn btn-warning btn-sm">Editar</a>
              <form action="{{ route('form-definitions.destroy', $formDefinition->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Excluir</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
