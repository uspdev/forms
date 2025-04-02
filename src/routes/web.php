<?php

use Illuminate\Support\Facades\Route;
use Uspdev\Forms\Http\Controllers\FormController;
use Uspdev\Forms\Http\Controllers\FormDefinitionController;

Route::group(['prefix' => config('uspdev-forms.prefix'), 'middleware' => ['web']], function () {
    Route::resource('form-definitions', FormDefinitionController::class);
    Route::get('/disciplinas/find', [FormController::class, 'findDisciplina'])->name('form.disciplina.find');
    Route::get('/users/find', [FormController::class, 'findUser'])->name('form.user.find');
    Route::get('/form-submissions/{formDefinitionId}', [FormController::class, 'allSubmissions'])->name('form.submissions.all');
    Route::get('/form-submissions/{formDefinitionId}/{formSubmissionId}', [FormController::class, 'getSubmission'])->name('form.submissions.get');
    Route::get('/form-submissions/{formDefinitionId}/{formSubmissionId}/edit', [FormController::class, 'editSubmission'])->name('form.submissions.edit');
    Route::post('/form-submissions/{formDefinitionId}/{formSubmissionId}/edit', [FormController::class, 'updateSubmission'])->name('form.submissions.update');
    Route::delete('/form-submissions/{formDefinitionId}/{formSubmissionId}', [FormController::class, 'deleteSubmission'])->name('form.submissions.delete');

});