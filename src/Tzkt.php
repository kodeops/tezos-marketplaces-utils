<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;

class Tzkt
{
    public static function permalink(string $address)
    {
        return "https://tzkt.io/{$address}";
    }

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

    public static function accountOperations(string $address, array $params = null)
    {
        if ($params) {
            $params = "?" . http_build_query($params);
        }

        return Http::get(self::baseUrl() . "/v1/accounts/{$address}/operations{$params}")
            ->throw()
            ->json();
    }
}
