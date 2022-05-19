<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Date;

class Coinlayer
{
    // https://coinlayer.com/documentation
    // Coinlayer allows 100 free requests a month

    const CACHE_PREFIX = 'kodeops.TezosMarketplacesUtils';
    const CACHE_EXPIRES_IN = 60 * 60 * 60;

    public static function baseUrl()
    {
        return env('COINLAYER_BASE_URL');
    }

    public static function tezos($target = 'USD')
    {
        $cache_key = self::CACHE_PREFIX . '.coinlayer.tezos.live';
        
        return Cache::remember($cache_key, self::CACHE_EXPIRES_IN, function () use ($target) {
            $params = [
                'access_key' => env('COINLAYER_API_KEY'),
                'symbols' => 'XTZ',
                'target' => $target,
            ];

            $data = Http::get(self::baseUrl() . "/live?" . http_build_query($params))
                ->throw()
                ->json();

            return $data['rates'][$params['symbols']];
        });
    }

    public static function tezosInDate(Date $date, $target = 'USD')
    {
        $cache_key = self::CACHE_PREFIX . '.coinlayer.tezos.date.' . $date;

        return Cache::remember($cache_key, self::CACHE_EXPIRES_IN, function () use ($date, $target) {
            $params = [
                'access_key' => env('COINLAYER_API_KEY'),
                'symbols' => 'XTZ',
                'target' => $target,
            ];

            $data = Http::get(self::baseUrl() . "/{$date->format('Y-m-d')}?" . http_build_query($params))
                ->throw()
                ->json();

            return $data['rates'][$params['symbols']];
        });
    }
}
