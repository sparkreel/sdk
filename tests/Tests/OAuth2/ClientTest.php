<?php
namespace Sparkreel\Tests\OAuth2;

use Sparkreel\Sdk\OAuth2\Client;
use Guzzle\Tests\GuzzleTestCase;

class ClientTest extends GuzzleTestCase
{
  public function testFactory()
  {
    $client = Client::factory(array(
        'scheme' => 'http',
        'hostname' => 'www.dev.sparkreel.com',
        'client_id' => 'demoreel',
        'client_secret' => 'demo_secret'
    ));
    $this->assertEquals("http://www.dev.sparkreel.com/oauth", $client->getBaseUrl(true));
    $this->assertEquals('demoreel', $client->getConfig('client_id'));
    $this->assertEquals('demo_secret', $client->getConfig('client_secret'));
  }
}
