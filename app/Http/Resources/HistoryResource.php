<?php

namespace App\Http\Resources;

use App\Models\Pokemon;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price = $this->pokemon->base_experience * Pokemon::$satoshi * $this->dailyPrice->price * $this->quantity;
        return [
            'id' => $this->id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'date' => $this->date->format('d/m/Y'),
            'pokemon_name' => $this->pokemon->name,
            'price' => '$ ' . number_format($price, 2),
        ];
    }
}
