<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pokemons';

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Constants
     */
    public static $satoshi = 0.00000001;


    /**
     * Retrieves pokemons filtered by request params.
     *
     * @param Array $requestFields
     *
     * @return Pokemon[]
     */
    public static function filter($requestFields)
    {
        $params = [
            'pokemons.*',
        ];

        $query = self::query();

        if (isset($requestFields['pokemon_name'])) {
            $query->where(
                'name',
                'like',
                '%' . $requestFields['pokemon_name'] . '%'
            );
        }

        return $query->select($params)->limit(5)->get();
    }
}
