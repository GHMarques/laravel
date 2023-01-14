<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\DailyPrice;
use Illuminate\Console\Command;

class FillDailyPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyPrice:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get BTC/USD from CoinAPI';

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
        $response = $client->get('https://rest-sandbox.coinapi.io/v1/exchangerate/BTC/USD?apikey=3E4EDC68-6636-4CF4-9D2F-3C3783F50C15');
        $exchangerate = json_decode($response->getBody()->getContents(), true);
        $dailyPrice = new DailyPrice();
        $dailyPrice->date = Carbon::now();
        $dailyPrice->price = $exchangerate['rate'];
        $dailyPrice->save();

        return 0;
    }
}
