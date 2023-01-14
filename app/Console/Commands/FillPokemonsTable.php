<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Pokemon;
use Illuminate\Console\Command;

class FillPokemonsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemons:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill pokemons table with PokeAPI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://pokeapi.co/api/v2/pokemon?offset=0&limit=1279');
        $pokemons = json_decode($response->getBody()->getContents(), true);
        $data = [];
        foreach ($pokemons['results'] as $pokemon) {
            $pokemonDetail = $client->get($pokemon['url']);
            $pokemonDetail = json_decode($pokemonDetail->getBody()->getContents(), true);
            if ($pokemonDetail['base_experience']) {
                $data[] = [
                    'name' => $pokemon['name'],
                    'base_experience' => $pokemonDetail['base_experience'],
                ];
            }
        }

        Pokemon::insert($data);
        return 0;
    }
}
