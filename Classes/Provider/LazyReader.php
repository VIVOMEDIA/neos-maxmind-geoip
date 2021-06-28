<?php


namespace VIVOMEDIA\MaxMind\GeoIp\Provider;


use GeoIp2\Database\Reader;
use GeoIp2\ProviderInterface;

class LazyReader implements ProviderInterface
{
    protected string $databasePath;

    protected ?ProviderInterface $provider = null;

    public function __construct($databasePath)
    {
        $this->databasePath = $databasePath;
    }

    public function country(string $ipAddress): \GeoIp2\Model\Country
    {
        $this->ensureProviderInitialized();
        return $this->provider->country($ipAddress);
    }

    public function city(string $ipAddress): \GeoIp2\Model\City
    {
        $this->ensureProviderInitialized();
        return $this->provider->city($ipAddress);
    }

    protected function ensureProviderInitialized()
    {
        if (!$this->provider) {
            $this->provider = new Reader($this->databasePath);
        }
        return $this->provider;
    }
}