<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::firstOrNew(["name" => "Burger"]);
        if ($product->id === null) {
            $product->sku = rand(99999999, 99999999999);
            $product->save();
        }
    }
}
