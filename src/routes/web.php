<?php

use Illuminate\Support\Facades\Route;
use Uspdev\Forms\Http\Controllers\FormController;
use Uspdev\Forms\Http\Controllers\FormDefinitionController;

Route::group(['prefix' => config('uspdev-forms.prefix'), 'middleware' => ['web']], function () {
    Route::resource('form-definitions', FormDefinitionController::class);
    Route::get('/disciplinas/find', [FormController::class, 'findDisciplina'])->name('form.disciplina.find');
    Route::get('/users/find', [FormController::class, 'findUser'])->name('form.user.find');
});