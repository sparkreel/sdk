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

class SparkreelTest extends GuzzleTestCase {

    /**
     * Test that the group videos response is parsed correctly
     */
    public function testGetGroupVideosOk()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("group1videos"));

        $result = $client->getGroupVideos(1);

        $this->assertArrayHasKey("videos", $result);
        $this->assertCount(20, $result['videos']);
    }

    /**
     * Test that we can submit videos to a group
     */
    public function testPostNmContentOk()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("postNMContentOK"));

        $videoFile = "@".TEST_VIDEOS_PATH."/test1.mp4";

        $res = $client->publishNonMemberContent("test@sparkreel.com", $videoFile, "testersr@mailinator.com", "phpUnit");

        $this->assertArrayHasKey("error", $res);
        $this->assertFalse($res["error"]);
    }

}