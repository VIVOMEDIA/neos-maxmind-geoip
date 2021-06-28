<?php


namespace VIVOMEDIA\MaxMind\GeoIp\Factory;

use Neos\Flow\Annotations as Flow;
use VIVOMEDIA\MaxMind\GeoIp\Provider\LazyReader;

/**
 * @Flow\Scope("prototype")
 */
class ReaderFactory
{
    public function create()
    {
        $databasePath = FLOW_PATH_DATA . 'Persistent/MaxMind/GeoLite2-Country.mmdb';
        return new LazyReader($databasePath);
    }
}