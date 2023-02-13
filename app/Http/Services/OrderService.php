<?php

namespace App\Http\Services;

use App\Models\Order;
use Exception;
use Throwable;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{

    public function validateAndProcessIngredient(array $products)
    {
        try {

            $isHalf = [];

            DB::beginTransaction();

            foreach ($products as $product) {

                $ingredients = Product::find(["id" => $product["product_id"]])->first()->ingredients()->lockForUpdate()->get();

                $ingredients->map(function ($ingredient) use ($product, &$isHalf) {
                    $ingredientWeight = ($ingredient->pivot->ingredient_weight) * $product["quantity"];

                    $availableIngredient = $ingredient->current_weight_in_grams;

                    // if the total required ingredient is greater than the ingredient then fail
                    if ($ingredientWeight > $availableIngredient) {
                        throw new Exception(sprintf("product id %s ingredient %s", $product["product_id"], $ingredient->id));
                    } else {
                        $ingredient->current_weight_in_grams = $availableIngredient - $ingredientWeight;
                        $ingredient->save();

                        // check if ingredient initial_weight_in_grams/2 is less or greater ingredient current_weight_in_grams
                        if (($ingredient->initial_weight_in_grams / 2) >= $ingredient->current_weight_in_grams) {
                            array_push($isHalf, $ingredient->id);
                        }
                    }
                });
            }

            DB::commit();

            return [
                "status" => true,
                "data" => $isHalf,
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

    public function storeOrder($products)
    {
        try {
            $order = new Order;
            $order->products = json_encode($products);
            $order->save();
        } catch (Throwable $th) {
            Log::critical("unable to save order to db", ["method" => "OrderService::storeOrder", "error" => $th->getMessage()]);
        }
    }
}
