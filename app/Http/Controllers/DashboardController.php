<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pokemon;
use App\Models\DailyPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Http\Controllers\TransactionController;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboard()
    {
        try {
            $user = User::latest()->first();
            $portifolio = TransactionController::getPortifolioPokemon();

            $total = 0;
            $dailyPrice = DailyPrice::latest()->first();
            foreach ($portifolio as $key => $quantity) {
                $pokemon = Pokemon::where('id', $key)->first();
                $total += $pokemon->base_experience * Pokemon::$satoshi * $dailyPrice->price;
            }

            $response = collect(['name' => $user->name, 'total' => '$ ' . number_format($total, 2)]);

            return DashboardResource::collection($response);
        } catch (Throwable $exception) {
            return $this->sendServerError($exception, __CLASS__, __FUNCTION__);
        }
    }
}
