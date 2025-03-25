<?php

use Uspdev\Forms\Http\Controllers\DisciplinaController;
use Illuminate\Support\Facades\Route;

if (config('uspdev-forms.disciplinaRoutes')) {
Route::get(config('uspdev-forms.disciplinaRoutes') . '/find', [DisciplinaController::class, 'find'])->name('DisciplinaFind');
}