<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Coinlayer
{
    public static function baseUrl()
    {
        return env('COINLAYER_BASE_URL');
    }

    public static function tezos()
    {
        $cache_key = 'kodeops\TezosMarketplacesUtils.coinlayer.tezos';

        // Coinlayer allows 100 free requests a month
        return Cache::remember($cache_key, 8 * 60 * 60, function () {
            $params = [
                'access_key' => env('COINLAYER_API_KEY'),
                'symbols' => 'XTZ',
            ];

            $data = Http::get(self::baseUrl() . "/live?" . http_build_query($params))
                ->throw()
                ->json();

            return $data['rates'][$params['symbols']];
        });
    }
}
