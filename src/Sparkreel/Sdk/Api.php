<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 1:19 PM
 */

namespace Sparkreel\Sdk;

use Sparkreel\Sdk;

class Api
{
    /** @var \Sparkreel\Sdk\SparkreelClient  */
    private $client = null;

    /**
     * Construct an API client.
     *
     * To setup your own Guzzle client instance set $baseUrl and $options to null,
     * or anything else as they will be ignored, and use the $client parameter. This
     * is specially meant to be helpful in testing.
     *
     * @param string                 $baseUrl
     * @param array                  $options
     * @param \Guzzle\Service\Client $client
     */
    public function __construct($baseUrl = "https://www.sparkreel.com/api", $options=array(), \Guzzle\Service\Client &$client = null)
    {
        if ($client === null) {
            $options['base_url'] = $baseUrl;
            $this->client = SparkreelClient::factory($options);
        } else {
            $this->client = $client;
        }

    }

    /**
     * Get an array containing all videos for a group.
     *
     * @param  int        $groupId
     * @param  int        $limit
     * @param  int        $page
     * @return array|bool
     */
    public function getGroupVideos($groupId, $limit = 10, $page = 1)
    {
        $result = false;

        try {
            $result = $this->client->getGroupVideos($groupId, $page, $limit);
            $result = $result["videos"];
        } catch (Exception $e) {}

        return $result;
    }
}
