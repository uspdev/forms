@extends('uspdev-forms::layouts.app')

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
                  {{-- 
                    Verifica se o diretório que guarda os formulários existe.
                    Caso exista, exibe o número de backups do formulário existem dentro dele.
                    Senão, mostra 0.
                  --}}
                  {{ is_dir(config('uspdev-forms.forms_storage_dir')) ? count(array_filter(scandir(config('uspdev-forms.forms_storage_dir')), fn($filename) => str_contains($filename,$formDefinition->name))) : 0 }}
                </span>
              </td>
              <td>
                {{ $formDefinition->group }}
              </td>
              <td class="d-flex justify-content-start align-item-centered">
                {{-- Botão para gerar o backup da definição --}}
                @include('uspdev-forms::definition.partials.bckpgen-btn')
                @include('uspdev-forms::definition.partials.bckplist-btn')
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
{{-- Botão de backup geral (todas as definições, separadamente) --}}
<br>@include('uspdev-forms::definition.partials.globalbckp-btn')
@endsection
