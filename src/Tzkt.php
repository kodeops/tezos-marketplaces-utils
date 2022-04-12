<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;

class Tzkt
{
    public static function baseUrl()
    {
        return env('TZKT_RPC_URL') ?? 'https://api.tzkt.io';
    }

    public static function account($address)
    {
        return Http::get(self::baseUrl() . "/v1/accounts/{$address}")
            ->throw()
            ->json();
    }
}
