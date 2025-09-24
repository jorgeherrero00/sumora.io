<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MeetingController;
Route::get('/ping', fn() => response()->json(['pong' => true]));

 Route::middleware('auth.api_token')->post('/upload', [MeetingController::class, 'uploadFromExtension']);
 Route::options('/upload', function () {
    return response()->json(['status' => 'ok'], 200);
});

/* Route::post('/upload', function (Request $request) {
    return response()->json([
        'ok' => true,
        'headers' => $request->headers->all(),
    ]);
});
 */