<?php

use Uspdev\Forms\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('uspdev-forms.prefix'), 'middleware' => ['web']], function () {
    Route::get('/disciplinas/find', [FormController::class, 'findDisciplina'])->name('form.disciplina.find');
    Route::get('/users/find', [FormController::class, 'findUser'])->name('form.user.find');
    Route::get('/create', [FormController::class, 'create'])->name('form-definitions.create');
    Route::post('/store', [FormController::class, 'store'])->name('form-definitions.store');
    Route::get('/index', [FormController::class, 'index'])->name('form-definitions.index');
    Route::get('/edit/{id}', [FormController::class, 'edit'])->name('form-definitions.edit');
    Route::put('/update/{id}', [FormController::class, 'update'])->name('form-definitions.update');
    Route::delete('/delete/{id}', [FormController::class, 'destroy'])->name('form-definitions.destroy');
});