@extends('laravel-usp-theme::master')

@section('content')
  <div class="container mt-3">
    <h2>{{ isset($formDefinition) ? 'Editar' : 'Criar' }} Form Definition</h2>

    <form
      action="{{ isset($formDefinition) ? route('form-definitions.update', $formDefinition->id) : route('form-definitions.store') }}"
      method="POST">
      @csrf
      @isset($formDefinition)
        @method('PUT')
      @endisset
      <div class="form-group">
        <label for="name">Nome do formulário</label>
        <input type="text" id="name" name="name" class="form-control"
          value="{{ old('name', $formDefinition->name ?? '') }}" required>
      </div>

      <div class="form-group">
        <label for="group">Grupo</label>
        <input type="text" id="group" name="group" class="form-control"
          value="{{ old('group', $formDefinition->group ?? '') }}" required>
      </div>

      <div class="form-group">
        <label for="description">Descrição</label>
        <input type="text" id="description" name="description" class="form-control"
          value="{{ old('description', $formDefinition->description ?? '') }}" required>
      </div>

      <div class="form-group">
        <label for="fields">Campos (json)</label>
        <textarea id="fields" name="fields" class="form-control autoexpand" required>
          {{ old('fields') ?? (isset($formDefinition) ? json_encode($formDefinition->fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}
        </textarea>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Salvar</button>
      <a href="{{ route('form-definitions.index') }}" class="btn btn-secondary btn-sm mr-2 mt-3 pb-2">
        <i class="fas fa-arrow-left"></i> Voltar
      </a>
    </form>
  </div>
@endsection

{{--
Bloco para autoexpandir textarea conforme necessidade.

Uso:
- Incluir no layouts.app ou em outro lugar: @include('laravel-usp-theme::blocos.textarea-autoexpand')
- Adiconar a classe 'autoexpand'

@author Masakik, em 8/5/2024
--}}
@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {

        //{{-- https://stackoverflow.com/questions/2948230/auto-expand-a-textarea-using-jquery --}}
        $(document).on('change keyup paste cut', '.autoexpand', function(e) {
          $(this).height(0).height(this.scrollHeight)
          // $(this).height(0).height(
          //   this.scrollHeight +
          //   parseFloat($(this).css('borderTopWidth')) +
          //   parseFloat($(this).css('borderBottomWidth'))
          // )
        })

        // aparentemente precisa dar um tempinho para poder disparar o autoexpand
        setTimeout(() => {
          $('.autoexpand').trigger('change')
        }, 500)

        $('#form-definition-form').on('submit', function(e) {
          const jsonText = $('#fields').val()

          try {
            JSON.parse(jsonText)
          } catch (error) {
            e.preventDefault();
            alert('O JSON precisa ser válido!')
          }
        })

      })
    </script>
  @endsection
@endonce
