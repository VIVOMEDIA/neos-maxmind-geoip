<?php

namespace VIVOMEDIA\MaxMind\GeoIp\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\Client\Browser;
use Neos\Flow\Http\Client\CurlEngine;
use PharData;

/**
 * @Flow\Scope("singleton")
 */
class DatabaseCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @Flow\InjectConfiguration
     * @var array
     */
    protected $configuration;

    public function updateCommand()
    {
        $maxMindDirectory = FLOW_PATH_DATA . 'Persistent/MaxMind/';

        $licenseKey = $this->configuration['licenceKey'];

        if (!$licenseKey) {
            throw new \RuntimeException('No licence key configured');
        }

        $uri = sprintf('https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key=%s&suffix=tar.gz', $licenseKey);

        $browser = new Browser();
        $browser->setRequestEngine(new CurlEngine());
        $request = $browser->request($uri);

        if (
            $request->getStatusCode() === 200 &&
            $request->getHeader('Content-Type')[0] === 'application/gzip'
        ) {
            if (!file_exists($maxMindDirectory)) {
                mkdir($maxMindDirectory);
            }
            var_dump($request->getHeaders());
            file_put_contents($maxMindDirectory . 'MaxMindGeoIp.tar.gz', $request->getBody());

            $phar = new PharData($maxMindDirectory . 'MaxMindGeoIp.tar.gz');
            $directoryName = $phar->getFilename();
            $phar->extractTo($maxMindDirectory, $directoryName . '/GeoLite2-Country.mmdb', true);

            rename($maxMindDirectory . $directoryName . '/GeoLite2-Country.mmdb', $maxMindDirectory . 'GeoLite2-Country.mmdb');

            if (file_exists($maxMindDirectory . '/MaxMindGeoIp.tar.gz')) unlink($maxMindDirectory . 'MaxMindGeoIp.tar.gz');
            if (file_exists($maxMindDirectory . $directoryName . '/GeoLite2-Country.mmdb')) unlink($maxMindDirectory . $directoryName . '/GeoLite2-Country.mmdb');
            if (file_exists($maxMindDirectory . $directoryName)) rmdir($maxMindDirectory . $directoryName);
        }
    }
}
