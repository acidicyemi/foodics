<?php

namespace App\Http\Interface;

interface OrderInterface
{
    /**
     * validate the if the total ingredient is available in the store
     *
     * @param array $products
     * 
     * @return array $response 
     */
    public function validateIngredientWeight(array $products);
}
