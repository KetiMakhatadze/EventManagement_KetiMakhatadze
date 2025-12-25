<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->get('/test', function () {
    return response()->json(['status' => 'ok']);
});
