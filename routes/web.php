<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API çalışıyor!',
        'timestamp' => now(),
    ]);
});


Route::get('/', function () {
    return redirect('/admin');
});
