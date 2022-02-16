<?php
namespace kodeops\TezosMarketplacesUtils;

use kodeops\TezosMarketplacesUtils\Address;

class ObjktCom
{
    const ENDPOINT = "https://objkt.com";

    public static function address($account, $shorten = true)
    {
        if (! empty($account['alias'])) {
            return $account['alias'];
        }

        if (! empty($account['tzdomain'])) {
            return $account['tzdomain'];
        }

        if (! empty($account['site'])) {
            return $account['site'];
        }

        $profile = Address::name($account['address']);
        if ($profile) {
            return $profile;
        }

        if ($shorten) {
            return Address::shorten($account['address']);
        }

        return $account['address'];
    }

    public static function tokenUrl($item)
    {
        return self::ENDPOINT . "/{$item['fa2']['contract']}/{$item['token']['token_id']}";
    }

    public static function addressUrl($address)
    {
        return self::ENDPOINT . "/profile/{$$address}";
    }
}
