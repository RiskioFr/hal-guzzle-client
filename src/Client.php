<?php
namespace Riskio\HalGuzzleClient;

use Guzzle\Service\Client as BaseClient;
use Riskio\HalGuzzleClient\Iterator\HalResourceIterator;

class Client extends BaseClient
{
    /**
     * {@inheritdoc}
     */
    public function __construct($baseUrl = '', $config = null)
    {
        parent::__construct($baseUrl, $config);

        $this->setConfig([self::CURL_OPTIONS => [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
        ]]);

        $this->setDefaultOption('headers', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Allow magic method calls for iterators (e.g. $client-><CommandName>Iterator($params))
     *
     * {@inheritdoc}
     */
    public function __call($method, $args = [])
    {
        if (substr($method, -8) === 'Iterator') {
            $commandOptions  = isset($args[0]) ? $args[0] : [];
            $iteratorOptions = isset($args[1]) ? $args[1] : [];
            $command         = $this->getCommand(substr($method, 0, -8), $commandOptions);

            return new HalResourceIterator($command, $iteratorOptions);
        }

        return parent::__call(ucfirst($method), $args);
    }
}
