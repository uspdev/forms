@extends('uspdev-forms::layouts.app')

@section('content')

<div class="card">
    <div class="card-header h4 card-header-sticky d-flex justify-content-between align-items-center">
      <div>
        <span class="text-danger">Definition Backups</span> > {{ $formDefinition->name }} >
        <a href="{{ route('form-definitions.backups') }}" class="btn btn-sm btn-outline-secondary ml-2">Voltar</a>
      </div>
      <div>
        @include('uspdev-forms::partials.ajuda-modal')
      </div>
    </div>
    <div class="card-body">
      <div>@include('uspdev-forms::definition.partials.bckpgen-btn')</div>
      <br>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Data de criação</th>
            <th>Última modificação</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($backup_data as $created_time => $updt_time)
            <tr>
              <td>
                {{ str_replace('_',' - ',str_replace('-','/',$created_time)) }}
              </td>
              <td>
                {{ str_replace('_',' - ',str_replace('-','/',$updt_time)) }}
              </td>
              <td class="d-flex justify-content-start align-item-centered">
                @include('uspdev-forms::definition.partials.restore-btn')
                @include('uspdev-forms::definition.partials.bckpremove-btn')
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
