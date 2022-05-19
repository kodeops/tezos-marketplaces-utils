<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Coinlayer
{
    const CACHE_PREFIX = 'kodeops.TezosMarketplacesUtils';
    const CACHE_EXPIRES_IN = 60 * 60 * 60;
    public static function baseUrl()
    {
        return env('COINLAYER_BASE_URL');
    }

    public static function tezos()
    {
        $cache_key = self::CACHE_PREFIX . '.coinlayer.tezos.live';
        
        return Cache::remember($cache_key, self::CACHE_EXPIRES_IN, function () {
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
