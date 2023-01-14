<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PokemonResource;
use App\Http\Requests\StorePokemonRequest;
use App\Http\Requests\UpdatePokemonRequest;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $filter)
    {
        try {
            $pokemons = Pokemon::filter($filter);
            return PokemonResource::collection($pokemons);
        } catch (Throwable $exception) {
            return $this->sendServerError($exception, __CLASS__, __FUNCTION__);
        }
    }
}
