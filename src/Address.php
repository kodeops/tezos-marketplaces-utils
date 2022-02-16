<?php
namespace kodeops\TezosMarketplacesUtils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use kodeops\LaravelGraphQL\Query;

use kodeops\TezosMarketplacesUtils\TezosMarketplacesUtilsException;
use SalesBot\HenQueries;

class Address
{
    const CACHE_PREFIX = 'TezosMarketplacesUtils';
    const CACHE_EXPIRES_IN = 60 * 60 * 60;

    public static function name($address)
    {
        $tzprofile = self::tzprofiles($address);
        if ($tzprofile) {
            return $tzprofile['alias'];
        }

        $hen = self::hen($address);
        if ($hen) {
            return $hen;
        }
    }

    public static function twitter($address)
    {
        $tzprofile = self::tzprofiles($address);
        if ($tzprofile AND isset($tzprofile['twitter'])) {
            return $tzprofile['twitter'];
        }
    }

    public static function hen($address)
    {
        $cache_key = self::CACHE_PREFIX . ".{$address}";
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        if (! env('TMU_HEN_INDEXER')) {
            throw new TezosMarketplacesUtilsException("TMU_HEN_INDEXER is missing");
        }

        // Resolve the name using hic_et_nunc_holder

        $query = 'query GenericQuery($address: String) {
            hic_et_nunc_holder(where: {address: {_eq: $address}}) {
                name
            }
        }';

        $profile = (new Query(env('TMU_HEN_INDEXER')))->resolve($query, ['address' => $address])['data'];

        if (empty($profile[0]['name'])) {
            return;
        }

        $profile = $profile[0]['name'];
        Cache::put($cache_key, $profile, self::CACHE_EXPIRES_IN);

        return $profile;
    }

    public static function tzprofiles($address)
    {
        $cache_key = self::CACHE_PREFIX . ".{$address}";
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $query = '{"query":"query MyQuery { tzprofiles_by_pk(account: \"' . $address . '\") { invalid_claims valid_claims contract } }","variables":null,"operationName":"MyQuery"}';
        $url = 'https://indexer.tzprofiles.com/v1/graphql';
        $request = Http::withBody($query, 'application/json')->post($url);

        if ($request->failed()) {
            return false;
            throw new TezosMarketplacesUtilsException("Profile verification failed using tzprofiles " . $request->body());
        }

        $response = $request->json();
        if (is_null($response['data']['tzprofiles_by_pk'])) {
            return false;
        }

        if (is_null($response['data']['tzprofiles_by_pk']['valid_claims'])) {
            return false;
        }

        $claims = [];
        foreach ($response['data']['tzprofiles_by_pk']['valid_claims'] as $claim) {
            $data = json_decode($claim[1], true);
            $claims[$data['type'][1]] = $data;
        }

        $profile = [];
        if (isset($claims['BasicProfile'])) {
            $profile['alias'] = $claims['BasicProfile']['credentialSubject']['alias'];
            $profile['website'] = $claims['BasicProfile']['credentialSubject']['website'];
            $profile['description'] = $claims['BasicProfile']['credentialSubject']['description'];
            $profile['logo'] = $claims['BasicProfile']['credentialSubject']['logo'];
        } elseif (isset($claims['TwitterVerification'])) {
            $profile['alias'] = str_replace("https://twitter.com/", "", $claims['TwitterVerification']['credentialSubject']['sameAs']);
            $profile['website'] = $claims['TwitterVerification']['credentialSubject']['sameAs'];
        }

        if (isset($claims['TwitterVerification'])) {
            $profile['twitter'] = $claims['TwitterVerification']['credentialSubject']['sameAs'];
        }

        Cache::put($cache_key, $profile, self::CACHE_EXPIRES_IN);

        return $profile;
    }

    public static function shorten($address)
    {
        return substr($address, 0, 4) . "..." . substr($address, -4);
    }
}
