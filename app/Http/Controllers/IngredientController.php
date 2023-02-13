<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockUpIngredientRequest;
use App\Http\Services\IngredientService;

class IngredientController extends Controller
{
    public $ingredientService;

    public function __construct(IngredientService $ingredientService)
    {
        $this->ingredientService = $ingredientService;
    }

    public function stockup(StockUpIngredientRequest $request)
    {
        $res = $this->ingredientService->stockUpIngredients($request->ingredients);

        if (!$res["status"]) {
            return json_response("unable to stockup", 400, ["error" => $res["data"]]);
        }
        return json_response("ingredients added successfully");
    }
}
