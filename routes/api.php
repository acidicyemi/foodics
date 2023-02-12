<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::group(["prefix" => "v1"], function () {
    Route::post("orders/accepts", [OrderController::class, "accepts"]); 
});