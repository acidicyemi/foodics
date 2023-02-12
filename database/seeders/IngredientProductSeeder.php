<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class IngredientProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::where(["name" => 'Burger'])->first();
        $product->ingredients()->attach(["ingredient_id" => "1"], ["ingredient_weight" => "150"]);
        $product->ingredients()->attach(["ingredient_id" => "2"], ["ingredient_weight" => "30"]);
        $product->ingredients()->attach(["ingredient_id" => "3"], ["ingredient_weight" => "20"]);
        $product->save();
    }
}
