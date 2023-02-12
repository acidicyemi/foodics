<?php

namespace App\Http\Services;

use Exception;
use Throwable;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{

    public function validateIngredientWeight(array $products)
    {
        try {

            DB::beginTransaction();

            foreach ($products as $product) {

                $productIngredients = Product::find(["id" => $product["product_id"]])->first()->ingredients;

                $productIngredients->map(function ($ingredient) use ($product) {
                    $ingredientWeight = ($ingredient->pivot->ingredient_weight) * $product["quantity"];

                    $availableIngredient = $ingredient->current_weight_in_grams;

                    // if the total ingredient is greater than the ingredient then fail
                    if ($ingredientWeight > $availableIngredient) {
                        throw new Exception(sprintf("product id %s ingredient %s", $product["product_id"], $ingredient->id));
                    } else {
                        $ingredient->current_weight_in_grams = $availableIngredient - $ingredientWeight;
                        $ingredient->save();
                    }
                });
            }

            DB::commit();

            return [
                "status" => true,
                "data" => '',
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error("insufficient ingredient to fulfil order", ["method" => "OrderService::validateIngredientWeight", "error" => $th->getMessage()]);
            return [
                "status" => false,
                "data" => $th->getMessage(),
            ];
        }
    }

}
