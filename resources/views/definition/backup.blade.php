@extends('uspdev-forms::layouts.app')

@section('header')
@endsection

@section('content')

<div class="col-2">@include('uspdev-forms::definition.partials.tabs')</div>
<div class="card">
    <div class="card-header h4 card-header-sticky d-flex justify-content-between align-items-center">
      <div>
        <span class="text-danger">Definition Backups</span>
      </div>
      <div>
        @include('uspdev-forms::partials.ajuda-modal')
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Grupo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($formDefinitions as $formDefinition)
            <tr>
              <td>
                {{ $formDefinition->name }}
                <span class="badge badge-warning badge-pill" title="Backups existentes">
                  {{ count(array_filter(scandir(config('uspdev-forms.forms_storage_dir')), fn($filename) => str_contains($filename,$formDefinition->name))) }}
                </span>
              </td>
              <td>
                {{ $formDefinition->group }}
              </td>
              <td class="d-flex justify-content-start align-item-centered">
                @include('uspdev-forms::definition.partials.bckpgen-btn')
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@include('uspdev-forms::definition.partials.globalbckp-btn')
@endsection
