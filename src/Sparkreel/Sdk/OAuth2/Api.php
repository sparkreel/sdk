<?php

namespace Sparkreel\Sdk\OAuth2;

use Guzzle\Service\Exception\ValidationException;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\Exception\CommandTransferException;

class Api
{
    /**
     * @var \Sparkreel\Sdk\OAuth2\Client
     */
    private $client = null;

    /**
     * Construct an API client.
     *
     * To setup your own Guzzle client instance set $baseUrl and $options to null,
     * or anything else as they will be ignored, and use the $client parameter. This
     * is specially meant to be helpful in testing.
     *
     * @param array                  $options
     * @param \Guzzle\Service\Client $client
     */
    public function __construct($options, \Guzzle\Service\Client &$client = null)
    {
        if ($client === null) {
            $this->client = Client::factory($options);
        } else {
            $this->client = $client;
        }
    }
    
    /**
     * 
     * @param string $authorizationCode
     * 
     * @throws ValidationException
     * @throws InvalidArgumentException if an invalid command is passed
     * @throws CommandTransferException if an exception is encountered when transferring multiple commands
     * 
     * @return array|\Guzzle\Http\Message\Response
     */
    public function requestAccessToken($authorizationCode)
    {
      $command = $this->client->getCommand('RequestAccessToken',
        array(
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code'
        ));

      return $this->client->execute($command);
    }
}
