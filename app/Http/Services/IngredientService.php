<?php

namespace App\Http\Services;

use Throwable;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use App\Mail\IngredientIsBelowHalf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IngredientService
{

    public function processMailNotification(array $ingredients)
    {
        try {
            DB::beginTransaction();

            $ingredients = Ingredient::lockForUpdate()->where("mail_sent", false)->whereIn("id", $ingredients);

            $data = $ingredients->get();

            if (count($data) > 0) {
                Mail::to(config("foodic.recipient_mail"))->queue(new IngredientIsBelowHalf($data));

                $ingredients->update(["mail_sent" => true]);
            }


            DB::commit();
        } catch (Throwable $th) {
            Log::error("unable to process mail", ["method" => "IngredientService::processMailNotification", "reason" => $th]);
            DB::rollBack();
        }
    }

    public function stockUpIngredients($ingredients)
    {
        try {

            DB::beginTransaction();

            foreach ($ingredients as $ingredient) {
                $i = Ingredient::lockForUpdate()->where("id", $ingredient["ingredient_id"])->first();
                $i->initial_weight_in_grams =  $i->current_weight_in_grams + $ingredient["weight_in_grams"];
                $i->current_weight_in_grams =  $i->current_weight_in_grams + $ingredient["weight_in_grams"];

                // business can determine if the added stock is still less than current_weight_in_grams then no other mail should be sent
                // but i will assume it should still be sent so toggle mail_sent to false to reset
                $i->mail_sent = false;
                $i->last_refilled = now();
                $i->save();
            }

            DB::commit();

            return ["status" => true, "data" => ""];
        } catch (Throwable $th) {
            DB::rollBack();
            return ["status" => false, "data" => $th->getMessage()];
        }
    }
}
