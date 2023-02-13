<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    const PATH = "/api/v1/ingredients/stockup";
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_that_when_payload_is_empty_422_is_returned()
    {
        // assign
        $data = ["ingredients" => []];

        // act
        $response = $this->postJson(self::PATH, $data);

        $response->assertInvalid(['ingredients']);

        // assert
        $response->assertStatus(422);
    }


    public function test_that_when_ingredient_id_doesnt_exist_a_validation_error()
    {
        // assign
        $data = $this->_wrong_data();

        // act
        $response = $this->postJson(self::PATH, $data);

        // assert
        $response->assertStatus(422);
    }


    public function test_that_when_the_right_data_is_passed_no_error_is_retured()
    {
        // assign
        $data = $this->_right_data();

        // act
        $response = $this->postJson(self::PATH, $data);

        // assert
        $response->assertStatus(200);
    }



    public function test_that_ingredient_is_stored_successful()
    {
        // assign
        $weight = 1000;
        $addedWeight = 1500;

        $ingredient = Ingredient::create(["name" => $this->faker->name, "current_weight_in_grams" => $weight, "initial_weight_in_grams" => $weight, "last_refilled" => now()]);
        $data = ["ingredients" => [["ingredient_id" => $ingredient->id, "weight_in_grams" => $addedWeight]]];

        // act
        $response = $this->postJson(self::PATH, $data);

        $currentState = Ingredient::where(["id" => $ingredient->id])->first();

        $this->assertInstanceOf(Ingredient::class, $currentState);
        $this->assertEquals(($weight + $addedWeight), $currentState->current_weight_in_grams);
        // assert
        $response->assertStatus(200);
    }




    public function _wrong_data()
    {
        return ["ingredients" => [["ingredient_id" => 0, "weight_in_grams" => 2]]];
    }

    public function _right_data()
    {
        return ["ingredients" => [["ingredient_id" => 1, "weight_in_grams" => 2]]];
    }
}
