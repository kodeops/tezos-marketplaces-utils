```
 _     _  _____  ______  _______  _____   _____  _______
 |____/  |     | |     \ |______ |     | |_____] |______
 |    \_ |_____| |_____/ |______ |_____| |       ______|
 
```
 

# Laravel Tezos Marketplaces Utils

## Coinlayer

A simple wrapper to get exchange rates

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