<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 3:56 PM
 */

namespace Sparkreel\Tests\Api;

use Guzzle\Tests\GuzzleTestCase;
use Sparkreel\Sdk\SparkreelClient;

class ApiTest extends GuzzleTestCase
{
    /**
     * Test that that we can get group videos
     */
    public function testGetGroupVideosOk()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("group1videos"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $videos = $api->getGroupVideos(20, 1);

        $this->assertArrayHasKey("videos", $videos);
        $this->assertCount(20, $videos['videos']);
    }

    /**
     * @expectedException \Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [per_page] must be of type integer
     */
    public function testInvalidLimitType()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("group1videos"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $videos = $api->getGroupVideos("non integer limit", 1);
    }

    /**
     * @expectedException \Guzzle\Service\Exception\ValidationException
     * @expectedExceptionMessage Validation errors: [page] must be of type integer
     */
    public function testInvalidPageType()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("group1videos"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $videos = $api->getGroupVideos(20, "non integer page");
    }

    public function testPublishNMContent()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("postNMContentOK"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $videoFile = TEST_VIDEOS_PATH."/test1.mp4";

        $res = $api->publishNonMemberContent($videoFile, "testersr@mailinator.com", "phpUnit");

        $this->assertArrayHasKey("content_id", $res);
        $this->assertEquals("393", $res["content_id"]);
    }

    /**
     * @expectedException \Guzzle\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unable to open fakeFile for reading
     */
    public function testPublishNMContentMissingFile()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("postNMContentOK"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $videoFile = "fakeFile";

        $res = $api->publishNonMemberContent($videoFile, "testersr@mailinator.com", "phpUnit");
    }

    public function testGetVideo()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getVideoOK"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $video = $api->getVideo(1);

        $this->assertEquals($video['id'], 1);
    }
}
