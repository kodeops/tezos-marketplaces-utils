<?php
namespace kodeops\TezosMarketplacesUtils;

use kodeops\TezosMarketplacesUtils\Address;
use kodeops\LaravelGraphQL\Query;

class ObjktCom
{
    const ENDPOINT = "https://objkt.com";

    public static function creators(array $creators, $glue = ', ', $shorten = true)
    {
        $addresses_formatted = '';
        foreach ($creators as $creator) {
            $addresses_formatted .= trim(self::address($creator['holder'], $shorten));
            if (last($creators) != $creator) {
                $addresses_formatted .= $glue;
            }
        }

        return $addresses_formatted;;
    }

    public static function address($account, $shorten = false)
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
        return self::ENDPOINT . "/asset/{$item['fa']['contract']}/{$item['token_id']}";
    }

    public static function addressUrl($address)
    {
        return self::ENDPOINT . "/profile/{$address}";
    }

    public static function holder($address)
    {
        $query = 'query MyQuery($address: String) {
          holder(where: {address: {_eq: $address}}) {
            address
            alias
            description
            email
            twitter
            tzdomain
          }
        }';

        $variables = ['address' => $address];

        $holder = (new Query(env('OBJKTCOM_INDEXER')))
            ->resolve($query, $variables)['data']['holder'];

        if (count($holder)) {
            return $holder[0];
        }
    }
}
