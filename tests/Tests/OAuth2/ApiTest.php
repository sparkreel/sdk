<?php
namespace Sparkreel\Tests\OAuth2;

use Guzzle\Tests\GuzzleTestCase;
use Sparkreel\Sdk\OAuth2\Api;

class ApiTest extends GuzzleTestCase
{
  public function testRequestAccessToken()
  {
    /**
     * @var \Sparkreel\Sdk\OAuth2\Client $client
     */
    $client = $this->getServiceBuilder()->get('test.oauth');
    $this->setMockResponse($client, array("oauth2.request-access-token.success"));

    $api = new Api(null, $client);
    $response = $api->requestAccessToken('4daf2a316f05859f33726f7c561cd384e0c69f21');

    $this->assertArrayHasKey("access_token", $response);
  }

  public function testRequestAccessTokenError()
  {
    /**
     * This gives us an exception instead of the response. How can we configure the
     * client to give us the response instead of throwing an exception?
     */
    $this->setExpectedException('\Guzzle\Http\Exception\ClientErrorResponseException');

    /**
     * @var \Sparkreel\Sdk\OAuth2\Client $client
     */
    $client = $this->getServiceBuilder()->get('test.oauth');
    $this->setMockResponse($client, array("oauth2.request-access-token.error"));

    $api = new Api(null, $client);
    $response = $api->requestAccessToken('fdce7677012f6367c84dcf64b7e52d86dad96fee');
  }
}
