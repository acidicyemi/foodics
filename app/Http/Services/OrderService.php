<?php

namespace App\Http\Services;

use Throwable;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderService 
{

    public function validateIngredientWeight(array $products)
    {
        try {

            foreach ($products as $product) {

                $productIngredients = Product::find(["id" => $product["product_id"]])->first()->ingredients;

                $productIngredients->map(function ($productIngredient) use ($product) {

                    $ingredentWeight = ($productIngredient->pivot->ingredient_weight) * $product["quantity"];

                    if ($ingredentWeight > $productIngredient->current_weight_in_grams) {
                        throw new Exception(sprintf("product id %s ingredient %s", $product["product_id"], $productIngredient->id));
                    }
                });
            }

            return [
                "status" => true,
                "data" => '',
            ];
        } catch (Throwable $th) {
            Log::error("insufficient ingredient to fulfil order", ["method" => "OrderService::validateIngredientWeight", "error" => $th->getMessage()]);
            return [
                "status" => false,
                "data" => $th->getMessage(),
            ];
        }
    }
}
