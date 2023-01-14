<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:m-d-Y',
    ];

    /**
     * Constants
     */
    const TYPE_BUY = 'buy';
    const TYPE_SELL = 'sell';

    public function dailyPrice()
    {
        return $this->belongsTo(DailyPrice::class);
    }

    public function pokemon()
    {
        return $this->belongsTo(Pokemon::class);
    }

    /**
     * Retrieves transactions filtered by request params.
     *
     * @param Integer $userId
     *
     * @return Transaction[]
     */
    public static function filter($userId)
    {
        $params = [
            'transactions.*',
        ];

        $query = self::query();

        $query->where(
            'user_id',
            $userId
        );

        return $query->select($params)->limit(20)->get();
    }
}
