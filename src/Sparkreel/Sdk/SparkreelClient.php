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
            'base_url' => '{scheme}://{hostname}',
            'scheme' => 'https',
            'hostname' => 'www.sparkreel.com'
        );

        $required = array('base_url');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/Resources/v0.json');
        $client->setDescription($description);

        return $client;
    }

    /**
     * Get an array containing all group videos under the "videos" key.
     *
     * @param int $id      The id of the group to get videos for
     * @param int $page    The page to retrieve, first page is number 1
     * @param int $perPage The number of videos per page, default is 20
     *
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getGroupVideos($id, $page=1, $perPage=20)
    {
        $command = $this->getCommand('GetGroupVideos', array('id' => $id, 'page'=>$page, 'per_page'=>$perPage));

        return $this->execute($command);
    }
}
