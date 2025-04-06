<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSubmissionController;

    Route::post('/submissions', [FormSubmissionController::class, 'store']);
    Route::get('/submissions', [FormSubmissionController::class, 'index']);
