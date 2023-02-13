<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ingredient;
use App\Events\NewOrderProcessed;
use App\Mail\IngredientIsBelowHalf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_that_when_payload_is_empty_422_is_returned()
    {
        // assign
        $data = ["products" => []];

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        $response->assertInvalid(['products']);

        // assert
        $response->assertStatus(422);
    }

    public function test_that_when_product_id_doesnt_exist_a_validation_error()
    {
        // assign
        $data = $this->_wrong_data();

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        // assert
        $response->assertStatus(422);
    }

    public function test_that_when_the_right_data_is_passed_no_error_is_retured()
    {
        // assign
        $data = $this->_right_data();

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        // assert
        $response->assertStatus(200);
    }

    public function test_that_the_order_is_stored_once()
    {
        // assign
        $data = $this->_right_data();

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        // assert
        $this->assertEquals(1, Order::count());
        $response->assertStatus(200);
    }

    public function test_that_the_order_reduces_the_ingredients_properly()
    {
        // assign
        $weight1 = 6000;
        $weight2 = 5000;
        $quantity = 2;

        $product = Product::create(["name" => $this->faker->name, "sku" => rand(11111111, 99999999)]);

        $ingredient1 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight1, "initial_weight_in_grams" => $weight1, "last_refilled" => now()]);
        $ingredient2 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight2, "initial_weight_in_grams" => $weight2, "last_refilled" => now()]);

        $data = ["products" => [["product_id" => $product->id, "quantity" => $quantity]]];

        // attach ingredient to order
        $product->ingredients()->attach(["ingredient_id" => $ingredient1->id], ["ingredient_weight" => "500"]);
        $product->ingredients()->attach(["ingredient_id" => $ingredient2->id], ["ingredient_weight" => "200"]);

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        $current1ngredient1 = Ingredient::where("id", $ingredient1->id)->first();
        $current1ngredient2 = Ingredient::where("id", $ingredient2->id)->first();

        // assert
        $this->assertInstanceOf(Ingredient::class, $current1ngredient1);
        $this->assertInstanceOf(Ingredient::class, $current1ngredient2);
        $this->assertEquals($current1ngredient1->current_weight_in_grams, ($weight1 - (500 * $quantity)));
        $this->assertEquals($current1ngredient2->current_weight_in_grams, ($weight2 - (200 * $quantity)));

        $response->assertStatus(200);
    }

    public function test_that_mail_was_not_queued_when_the_ingredients_is_not_up_to_50_percent()
    {
        Mail::fake();

        // assign
        $weight1 = 600;
        $weight2 = 300;
        $quantity = 1;

        $product = Product::create(["name" => $this->faker->name, "sku" => rand(11111111, 99999999)]);

        $ingredient1 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight1, "initial_weight_in_grams" => $weight1, "last_refilled" => now()]);
        $ingredient2 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight2, "initial_weight_in_grams" => $weight2, "last_refilled" => now()]);

        $data = ["products" => [["product_id" => $product->id, "quantity" => $quantity]]];

        // attach ingredient to order
        $product->ingredients()->attach(["ingredient_id" => $ingredient1->id], ["ingredient_weight" => "150"]);
        $product->ingredients()->attach(["ingredient_id" => $ingredient2->id], ["ingredient_weight" => "20"]);

        // act
        $this->postJson('/api/v1/orders/accepts', $data);

        Mail::assertNotQueued(IngredientIsBelowHalf::class);
    }

    public function test_that_mail_was_queued_when_the_ingredients_reaches_50_percent()
    {
        Mail::fake();

        // assign
        $weight1 = 600;
        $weight2 = 300;
        $quantity = 2;

        $product = Product::create(["name" => $this->faker->name, "sku" => rand(11111111, 99999999)]);

        $ingredient1 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight1, "initial_weight_in_grams" => $weight1, "last_refilled" => now()]);
        $ingredient2 = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight2, "initial_weight_in_grams" => $weight2, "last_refilled" => now()]);

        $quantity = 2;
        $data = ["products" => [["product_id" => $product->id, "quantity" => $quantity]]];

        // attach ingredient to order
        $product->ingredients()->attach(["ingredient_id" => $ingredient1->id], ["ingredient_weight" => "150"]);
        $product->ingredients()->attach(["ingredient_id" => $ingredient2->id], ["ingredient_weight" => "20"]);

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        Mail::assertQueued(IngredientIsBelowHalf::class);
    }

    public function test_that_event_was_properly_dispatch_on_success()
    {
        Event::fake();
        
        $data = $this->_right_data();

        // act
        $response = $this->postJson('/api/v1/orders/accepts', $data);

        Event::assertDispatched(NewOrderProcessed::class);
    }

    public function _wrong_data()
    {
        return ["products" => [["product_id" => 11, "quantity" => 2]]];
    }

    public function _right_data()
    {
        return ["products" => [["product_id" => 1, "quantity" => 2]]];
    }
}
