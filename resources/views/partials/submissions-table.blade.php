{{-- 

  Esta é uma diretiva blade.
  @submissionTable($form)
  
 --}}
<table class="table table-striped table-bordered datatable-simples">
  <thead>
    <tr>
      @foreach ($form->getDefinition()->flattenFields() as $field)
        <th>{{ $field['label'] }}</th>
      @endforeach
      @if ($form->editable)
        <th></th>
      @endif
    </tr>
  </thead>
  <tbody>
    @forelse ($form->listSubmission() as $submission)
      <tr>
        @foreach ($form->getDefinition()->flattenFields() as $field)
          <td>
            @if ($field['type'] == 'pessoa-usp')
              {{ $submission['data'][$field['name']] }}
              {{ \Uspdev\Replicado\Pessoa::retornarNome($submission['data'][$field['name']]) ?? $submission['data'][$field['name']] }}
            @elseif ($field['type'] == 'checkbox')
              {{ json_encode($submission['data'][$field['name']]) ?? 'n/a' }}
            @else
              {{ $submission['data'][$field['name']] ?? 'n/a' }}
            @endif
          </td>
        @endforeach
        @if ($form->editable)
          <td>
            <a href="{{ url()->current() }}/{{ $submission->id }}/edit" class="btn btn-sm btn-outline-primary">Edit</a>
          </td>
        @endif
      </tr>
    @empty
      <tr>
        <td class="text-center">Nenhuma submissão encontrada.</td>
      </tr>
    @endforelse
  </tbody>
</table>
