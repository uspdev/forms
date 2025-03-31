<?php

use Uspdev\Forms\Http\Controllers\FormDefinitionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('uspdev-forms.prefix'), 'middleware' => ['web']], function () {
    Route::get('/disciplinas/find', [FormDefinitionController::class, 'findDisciplina'])->name('form.disciplina.find');
    Route::get('/users/find', [FormDefinitionController::class, 'findUser'])->name('form.user.find');
    Route::get('/create', [FormDefinitionController::class, 'create'])->name('form-definitions.create');
    Route::post('/store', [FormDefinitionController::class, 'store'])->name('form-definitions.store');
    Route::get('/index', [FormDefinitionController::class, 'index'])->name('form-definitions.index');
    Route::get('/edit/{id}', [FormDefinitionController::class, 'edit'])->name('form-definitions.edit');
    Route::put('/update/{id}', [FormDefinitionController::class, 'update'])->name('form-definitions.update');
    Route::delete('/delete/{id}', [FormDefinitionController::class, 'destroy'])->name('form-definitions.destroy');
});