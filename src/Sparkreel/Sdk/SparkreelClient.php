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

        $required = array('base_url');
        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        // Attach a service description to the client
        $description = ServiceDescription::factory(__DIR__ . '/Resources/v0.json');
        $client->setDescription($description);

        return $client;
    }


    /**
     * Post a video content to a non member submission anabled group.
     *
     * @param string $groupEmail The group's email address
     * @param string $videoFile The video file in curl format. I.e. "@/filePath/file.avi"
     * @param string $email The sender's email address
     * @param string $title The content title
     * @param string $description The content description
     *
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishNonMemberContent($groupEmail, $videoFile, $email="", $title="", $description="")
    {
        $command = $this->getCommand('PublishNonMemberContent',
            array('groupemail' => $groupEmail,
                  'email'=>$email,
                  'title'=>$title,
                  'description'=>$description,
                  'file'=>$videoFile));

        return $this->execute($command);
    }
}
