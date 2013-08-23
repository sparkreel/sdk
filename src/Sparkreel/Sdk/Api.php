<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 1:19 PM
 */

namespace Sparkreel\Sdk;

use Guzzle\Service\Exception\ValidationException;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\Exception\CommandTransferException;

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
     * Get an array containing the response of the api. If successful,
     * the "videos" key will hold $limit number of videos for the requested
     * $page.
     *
     * @param int $groupId
     * @param int $limit
     * @param int $page
     *
     * @throws ValidationException
     * @throws InvalidArgumentException if an invalid command is passed
     * @throws CommandTransferException if an exception is encountered when transferring multiple commands
     * @return array
     */
    public function getGroupVideos($groupId, $limit = 10, $page = 1)
    {
        $result = $this->client->getGroupVideos($groupId, $page, $limit);

        return $result;
    }
}
