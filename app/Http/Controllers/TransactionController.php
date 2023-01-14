<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pokemon;
use App\Models\DailyPrice;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\PortifolioResource;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userId = User::latest()->first()->id;
            $transactions = Transaction::filter($userId);
            return HistoryResource::collection($transactions);
        } catch (Throwable $exception) {
            return $this->sendServerError($exception, __CLASS__, __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            $transaction = new Transaction();
            $transaction->type = $request->type;
            $transaction->quantity = $request->quantity;
            $transaction->date = Carbon::now();
            $transaction->daily_price_id = DailyPrice::latest()->first()->id;
            $transaction->pokemon_id = $request->pokemon_id;
            $transaction->user_id = User::latest()->first()->id;
            $transaction->save();

            return new TransactionResource($transaction);
        } catch (Throwable $exception) {
            return $this->sendServerError($exception, __CLASS__, __FUNCTION__);
        }
    }

    /**
     * Display the portifolio
     *
     * @return \Illuminate\Http\Response
     */
    public function getPortifolio()
    {
        try {
            $portifolio = $this->getPortifolioPokemon();

            $response = collect([]);
            $dailyPrice = DailyPrice::latest()->first();
            foreach ($portifolio as $key => $quantity) {
                $pokemon = Pokemon::where('id', $key)->first();
                $price = $pokemon->base_experience * Pokemon::$satoshi * $dailyPrice->price;
                $item = (object) [
                    'id' => $pokemon->id,
                    'name' => $pokemon->name,
                    'quantity' => $quantity,
                    'price' => '$ ' . number_format($price, 2),
                    'base_experience' => $pokemon->base_experience
                ];
                $response->push($item);
            }

            return PortifolioResource::collection($response);
        } catch (Throwable $exception) {
            return $this->sendServerError($exception, __CLASS__, __FUNCTION__);
        }
    }

    /**
     * Get portifolio
     *
     * @return Array
     */
    public static function getPortifolioPokemon()
    {
        $transactions = Transaction::where('user_id', User::latest()->first()->id)->get();
        $portifolio = array();

        foreach ($transactions as $transaction) {
            $multiplier = $transaction->type == Transaction::TYPE_BUY ? 1 : -1;
            if (array_key_exists($transaction->pokemon_id, $portifolio)) {
                $portifolio[$transaction->pokemon_id] += $transaction->quantity * $multiplier;
            } else {
                $portifolio[$transaction->pokemon_id] = $transaction->quantity * $multiplier;
            }
        }

        return $portifolio;
    }
}
