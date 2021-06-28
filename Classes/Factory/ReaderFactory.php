<?php


namespace VIVOMEDIA\MaxMind\GeoIp\Factory;

use GeoIp2\Database\Reader;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("prototype")
 */
class ReaderFactory
{
    public function create()
    {
        $databasePath = FLOW_PATH_DATA . 'Persistent/MaxMind/GeoLite2-Country.mmdb';
        return new Reader($databasePath);
    }
}