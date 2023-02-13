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
}
