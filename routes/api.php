<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IngredientController;

Route::group(["prefix" => "v1"], function () {
    Route::post("orders/accepts", [OrderController::class, "accepts"]); 
    Route::post("ingredients/stockup", [IngredientController::class, "stockup"]); 
});