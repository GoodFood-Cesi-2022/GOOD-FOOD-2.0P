<?php

namespace App\Http\Controllers\Api\Contractors\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\AcceptOrderRequest;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\RejectOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\ModelFilters\OrderFilter;
use App\Models\Order;
use App\Models\OrderState;
use App\Models\OrderStep;
use App\Models\VatCode;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    
    /**
     * Créé une nouvelle commande
     *
     * @param CreateOrderRequest $request
     * @return OrderResource
     */
    public function create(CreateOrderRequest $request) : OrderResource {

        $recipes_request = collect($request->recipes);
        $recipes_ids = $recipes_request->pluck('id')->toArray();

        $vat_code = VatCode::whereCode(strtolower($request->contractor->timezone))->first();

        $recipes = $request->contractor
            ->recipes()
            ->whereIn('recipe_id', $recipes_ids)
            ->get()
            ->map(function(\App\Models\Recipe $recipe) use ($recipes_request){
                $recipe_request = collect($recipes_request->where('id', $recipe->id)->first());
                $recipe->quantity = $recipe_request->get('quantity', 1);
                $recipe->comment = $recipe_request->get('comment', '');
                return $recipe;
            });

        $amount = $recipes->sum(function(\App\Models\Recipe $recipe) {
            return $recipe->pivot->price * $recipe->quantity;
        });

        $order = new Order([
            'amount' => $amount
        ]);

        $order->contractor()->associate($request->contractor);
        $order->address()->associate($request->address_id);
        $order->user()->associate(auth()->user());
        $order->vatCode()->associate($vat_code);
        
        $order->save();
        
        $this->attachStateToOrder($order, 'creating');

        $order->recipes()->sync($recipes->mapWithKeys(function(\App\Models\Recipe $recipe) {
            return [$recipe->id => [
                'quantity' => $recipe->quantity,
                'comment' => $recipe->comment,
                'price_unit' => $recipe->pivot->price
            ]];
        }));

        return new OrderResource($order);


    }

    /**
     * Retourne toutes les commandes du franchisé
     *
     * @param Request $request
     * @return \App\Http\Resources\OrderCollection
     */
    public function all(Request $request) : OrderCollection {

        $this->authorize('view-orders', $request->contractor);

        $orders = Order::whereContractorId($request->contractor->id)->filter($request->all(), OrderFilter::class)->get();

        return new OrderCollection($orders);

    }

    /**
     * Accepte la commande par le franchisé et passe la commande en statut préparation
     *
     * @param AcceptOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function accept(AcceptOrderRequest $request) : \Illuminate\Http\Response {

        $this->attachStateToOrder($request->order, 'cooking');

        return response('', 204);
    }

    /**
     * La commande est rejettée par le franchisé le statut de la commande passe en canceled
     *
     * @param RejectOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function reject(RejectOrderRequest $request) : \Illuminate\Http\Response {

        $this->attachStateToOrder($request->order, 'canceled');

        return response('', 204);
    }


    /**
     * Retourne une commande
     *
     * @param Request $request
     * @return OrderResource
     */
    public function retreive(Request $request) : OrderResource {

        $this->authorize('view', [$request->order, $request->contractor]);

        return new OrderResource($request->order);

    }


    /**
     * Attache un statut de la commande
     *
     * @param Order $order
     * @param string $state_code
     * @return OrderStep
     */
    private function attachStateToOrder(Order $order, string $state_code) : OrderStep {

        $order_state = OrderState::whereCode($state_code)->first();
        $order_step = new OrderStep();
        $order_step->orderState()->associate($order_state);
        $order->steps()->save($order_step);

        return $order_step;

    }


}
