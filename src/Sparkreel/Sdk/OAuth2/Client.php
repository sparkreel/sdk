<?php
namespace Sparkreel\Sdk\OAuth2;

use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Guzzle Client for SparkReels OAuth2 server. All commands are described in Resources/OAuth2.json
 *
 * @package Sparkreel\Sdk
 */
class Client extends GuzzleClient
{
    /**
     * Factory method to create the client
     *
     * @param  array  $config
     * @return Client
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => '{scheme}://{hostname}/oauth',
            'scheme' => 'https',
            'hostname' => 'www.sparkreel.com'
        );

        $required = array('base_url', 'client_id', 'client_secret');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(dirname(__DIR__) . '/Resources/OAuth2.json');
        $client->setDescription($description);

        // send client_id & client_secret as Basic auth
        $client->setDefaultOption('auth', array($config->get('client_id'), $config->get('client_secret'), 'Basic'));

        return $client;
    }

}
