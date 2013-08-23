<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 12:28 PM
 *
 * Test case for the sparkreel client
 */

namespace Sparkreel\Tests\Client;

use Guzzle\Tests\GuzzleTestCase;
use Sparkreel\Sdk\SparkreelClient;

class SparkreelTest extends GuzzleTestCase
{
    /**
     * Test that we can construct a client
     */
    public function testFactory()
    {
        $client =  SparkreelClient::factory();

        $this->assertEquals("https://api.sparkreel.com/v1", $client->getBaseUrl(true));
    }

}
