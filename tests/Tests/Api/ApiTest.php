<?php
/**
 * User: Tabaré Caorsi <tabare@heapstersoft.com>
 * Date: 8/21/13
 * Time: 3:56 PM
 */

namespace Sparkreel\Tests\Api;

use Guzzle\Log\MessageFormatter;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;
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
    
    public function testUpdateGroup()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("updateGroup"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);
        $group = $api->updateGroup('Group Title', 'Group Description', array(
            'watermark_url' => 'http://s3/watermark.png',
            'endframe_desktop_html' => '<h1>Endframe desktop</h1>',
            'endframe_mobile_html' => '<h1>Endframe mobile</h1>',
            'email_domain' => 'foobar.com'
        ));

        $this->assertEquals($group['group']['title'], 'Group Title');
        $this->assertEquals($group['group']['config']['watermark_url'], 'http://s3/watermark.png');
    }
    
    public function testGetGroupInfo()
    {
      $client = $this->getServiceBuilder()->get('test.sparkreel');
      $this->setMockResponse($client, array("getGroupInfo"));

      $api = new \Sparkreel\Sdk\Api(null, null, $client);
      $group = $api->getGroupInfo();

      $this->assertArrayHasKey('group', $group);
      $this->assertEquals($group['group']['title'], 'Group Title');
    }
    
    public function testUpdateVideo()
    {
      $client = $this->getServiceBuilder()->get('test.sparkreel');
      $this->setMockResponse($client, array("updateVideo"));

      $api = new \Sparkreel\Sdk\Api(null, null, $client);
      $response = $api->updateVideo(410, array('status' => 'rejected'));

      $this->assertTrue('rejected' == $response['video']['status']);
    }
    
    public function testDeleteVideo()
    {
      $client = $this->getServiceBuilder()->get('test.sparkreel');
      $this->setMockResponse($client, array("deleteVideo"));

      $api = new \Sparkreel\Sdk\Api(null, null, $client);
      $response = $api->deleteVideo(410);

      $this->assertTrue($response['success']);
    }
    
    public function testGetVideoComments()
    {
      $client = $this->getServiceBuilder()->get('test.sparkreel');
      $this->setMockResponse($client, array("getVideoComments"));

      $api = new \Sparkreel\Sdk\Api(null, null, $client);
      $response = $api->getVideoComments(387);

      $this->assertArrayHasKey('comments', $response);
    }
    
    public function testPublishMemberContentExternal()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("publishVideo"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $res = $api->publishMemberContentExternal('c2ab9c1f4ac8a2a99e45f70e1e19a4ada4d38436', 'title', 'desc', 'http://www.youtube.com/watch?v=mAtOV_cp2b8');

        $this->assertArrayHasKey("content_id", $res);
        $this->assertEquals("393", $res["content_id"]);
    }
    
    public function testPostComment()
    {
      /** @var  \Sparkreel\Sdk\SparkreelClient $client */
      $client = $this->getServiceBuilder()->get('test.sparkreel');
      $this->setMockResponse($client, array("postComment"));

      $api = new \Sparkreel\Sdk\Api(null, null, $client);

      $res = $api->postComment(387, 'c2ab9c1f4ac8a2a99e45f70e1e19a4ada4d38436', 'test comment');

      $this->assertArrayHasKey("comment", $res);
      $this->assertEquals("test comment", $res["comment"]["text"]);
    }

    public function testGetUserInfo()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getUserData"));
        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $res = $api->getUser(2);

        $this->assertArrayHasKey("user", $res);
    }

    public function testCurrentUserInfo()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getCurrentUserData"));
        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $token = "ba94ada9c8fee7b11addf9c386f201377f30d417";
        $res = $api->getUser(null, $token);

        $this->assertArrayHasKey("user", $res);
    }

    public function testUpdateCurrentUserInfo()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("updateUser"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $token = "ba94ada9c8fee7b11addf9c386f201377f30d417";
        $params = array(
            "name"      => "Taba API",
            "email"     => "taba@mailinator.com",
            "facebook"  => "tabafb",
            "twitter"   => "tabatw",
            "youtube"   => "tabayt",
            "quote"     => "desc from api",
        );
        $res = $api->updateUser($token, $params);

        $this->assertArrayHasKey("user", $res);

        foreach ($params as $key=>$val) {
            $this->assertEquals($val, $res['user'][$key]);
        }
    }

    public function testUpdateCurrentUserAvatar()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("postUserAvatar"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $token = "ba94ada9c8fee7b11addf9c386f201377f30d417";
        $file = $videoFile = TEST_IMAGES_PATH."/avatar.jpg";
        $res = $api->updateUserAvatar($token, $file);

        $this->assertArrayHasKey("avatar", $res);
    }

    public function testGetUserInfoByEmail()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getUserByUsername"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $res = $api->getUser(null, null, 'pyro');

        $this->assertArrayHasKey("user", $res);
    }

    public function testGetTopTen()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getTopTen"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $res = $api->getTopTenUsers();

        $this->assertArrayHasKey("topten", $res);
    }


    public function testGetGroupMembers()
    {
        /** @var  \Sparkreel\Sdk\SparkreelClient $client */
        $client = $this->getServiceBuilder()->get('test.sparkreel');
        $this->setMockResponse($client, array("getGroupMembers"));

        $api = new \Sparkreel\Sdk\Api(null, null, $client);

        $res = $api->getGroupMembers();

        $this->assertTrue(is_array($res));
        $this->assertGreaterThan(0, count($res));
    }

}
