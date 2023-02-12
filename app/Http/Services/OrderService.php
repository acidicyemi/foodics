<?php

namespace App\Http\Services;

use App\Http\Interface\OrderInterface;
use Throwable;

class OrderService implements OrderInterface
{

    public function validateIngredientWeight(array $products)
    {
        try {

            return [
                "status" => false,
                "data" => '',
            ];
        } catch (Throwable $th) {
            return [
                "status" => false,
                "data" => $th->getMessage(),
            ];
        }
    }
}
