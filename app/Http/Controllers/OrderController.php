<?php

namespace App\Http\Controllers;

use App\Events\NewOrderProcessed;
use App\Http\Services\OrderService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AcceptOrderRequest;

class OrderController extends Controller
{
    public $order;

    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    public function accepts(AcceptOrderRequest $request)
    {
        $res = $this->order->validateAndProcessIngredient($request->products);

        if (!$res["status"]) {
            Log::info("unable to fulfil order", ["method" => "OrderController::accepts", "reason" => "insufficient ingredient to fulfil order"]);
            return json_response("unable to fulfil order", 400);
        }

        // log order

        // emit event 
        NewOrderProcessed::dispatch($request->products);

        return json_response("order processing", 200);
    }
}
