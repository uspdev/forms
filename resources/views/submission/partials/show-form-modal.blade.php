<div class="modal fade" id="showFormSubmissionModal-{{ $submission->id }}" tabindex="-1" role="dialog" aria-labelledby="showFormSubmissionModalLabel-{{ $submission->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showFormSubmissionModalLabel-{{ $submission->id }}">
          Visualizar submissão #{{ $submission->id }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- longName ativo, mostra o codigo e o nome dos campos *-usp -->
        {!! $submission->showHtml(true, $form->admin ?? false) !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
