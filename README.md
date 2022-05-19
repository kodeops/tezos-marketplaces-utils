```
 _     _  _____  ______  _______  _____   _____  _______
 |____/  |     | |     \ |______ |     | |_____] |______
 |    \_ |_____| |_____/ |______ |_____| |       ______|
 
```
 

# Laravel Tezos Marketplaces Utils

## Coinlayer

Add `COINLAYER_BASE_URL` and `COINLAYER_API_KEY` to the environment file:

```
COINLAYER_BASE_URL=https://api.coinlayer.com
COINLAYER_API_KEY=api_key
```

Available targets: [https://coinlayer.com/target](https://coinlayer.com/target)

A simple wrapper to get exchange rates

### Live exchange rate

Gets the actual tezos exchange rate.

```
use kodeops\TezosMarketplacesUtils;

$rate = Coinlayer::tezos('EUR');

// $rate => '1.754402'
```

### Dated exchange rate

Gets the  tezos exchange rate for a given date.

```
use kodeops\TezosMarketplacesUtils;

$rate = Coinlayer::tezos('2021-01-01', EUR');

// $rate => '1.754402'
```

Response: [https://coinlayer.com/documentation](https://coinlayer.com/documentation)

```
{
  "success": true,
  "terms": "https://coinlayer.com/terms",
  "privacy": "https://coinlayer.com/privacy",
  "timestamp": 1529571067,
  "target": "USD",
  "rates": {
    "611": 0.389165,
    "ABC": 59.99,
    "ACP": 0.014931,
    "ACT": 0.15927,
    "ACT*": 0.14371,
    "ADA": 0.160502,
    "ADCN": 0.001406,
    "ADL": 121.5,
    "ADX": 0.427854,
    "ADZ": 0.02908,
    "AE": 2.551479,
    "AGI": 0.12555,
    "AIB": 0.005626,
    "AIDOC": 0.02605,
    [...]
  }
}
```

## Address Utils

### Name

Uses tzprofiles and Objkt.com indexer to fetch the name of the account (alias or tez domain)

```
use kodeops\TezosMarketplacesUtils;

$name = Address::name('tz1UKozV9Nr7L3AUyFwYbhDvXEXACiLHL9Yk');

// $name => 'tannhauser'
```

### Permalink

Get the permalink for a given address.

```
use kodeops\TezosMarketplacesUtils;

$permalink = Address::permalink('tz1UKozV9Nr7L3AUyFwYbhDvXEXACiLHL9Yk');

// $permalink => 'https://tzkt.io/tz1UKozV9Nr7L3AUyFwYbhDvXEXACiLHL9Yk'
```

### Twitter

Uses tzprofiles and Objkt.com indexer to fetch the twitter handle of the address (if available)

```
use kodeops\TezosMarketplacesUtils;

$twitter = Address::twitter('tz1UKozV9Nr7L3AUyFwYbhDvXEXACiLHL9Yk');

// $twitter => 'tannhauserxyz'
```

### Shorten address

Gets first 4 and last 4 digits of an address using a given separator

```
use kodeops\TezosMarketplacesUtils;

$address_short = Address::shorten('tz1UKozV9Nr7L3AUyFwYbhDvXEXACiLHL9Yk', '...');

// $address_short => 'tz1U...L9Yk'
```