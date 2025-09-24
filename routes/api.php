<?php 

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MeetingController;

Route::middleware('auth.api_token')->post('/upload', [MeetingController::class, 'uploadFromExtension']);
