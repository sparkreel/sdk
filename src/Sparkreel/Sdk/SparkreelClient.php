<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 10:50 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Sparkreel\Sdk;

use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

class SparkreelClient extends Client {

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

    public function getGroupVideos($id)
    {
        $command = $this->getCommand('GetGroupVideos', array('id' => $id));
        return $this->execute($command);
    }
}