<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\AcceptOrderRequest;
use App\Http\Services\OrderService;

class OrderController extends Controller
{
    public $order;

    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    public function accepts(AcceptOrderRequest $request)
    {
        $res = $this->order->validateIngredientWeight($request->products);

        if (!$res["status"]) {
            Log::info("unable to fulfil order", ["method" => "OrderController::accepts", "reason" => "insufficient ingredient to fulfil order"]);
            return json_response("unable to fulfil order", 400);
        }
        
        

        // emit event 

        return json_response("success");
    }
}
