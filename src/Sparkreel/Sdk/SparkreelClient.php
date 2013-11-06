<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 10:50 AM
 */

namespace Sparkreel\Sdk;

use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Class SparkreelClient
 *
 * Guzzle Client for sparkreels API. All commands are described in Resources/v0.json
 *
 * @package Sparkreel\Sdk
 */
class SparkreelClient extends Client
{
    /**
     * Factory method to create the client
     *
     * @param  array                  $config
     * @return Client|SparkreelClient
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => '{scheme}://{hostname}/v1',
            'scheme' => 'https',
            'hostname' => 'api.sparkreel.com'
        );

        $required = array('base_url', 'api_key');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        // set X-API-Key header
        $client->setDefaultOption('headers/X-API-Key', $config->get('api_key'));

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/Resources/v0.json');
        $client->setDescription($description);

        return $client;
    }

}
