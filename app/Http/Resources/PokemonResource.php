<?php

namespace App\Http\Resources;

use App\Models\Pokemon;
use App\Models\DailyPrice;
use Illuminate\Http\Resources\Json\JsonResource;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $dailyPrice = DailyPrice::latest()->first();
        $price = $this->base_experience * Pokemon::$satoshi * $dailyPrice->price;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'base_experience' => $this->base_experience,
            'price' => '$ ' . number_format($price, 2),
        ];
    }
}
