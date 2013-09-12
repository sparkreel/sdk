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
    public function __construct($baseUrl = "https://api.sparkreel.com/v1", $options=array(), \Guzzle\Service\Client &$client = null)
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
     * @param int    $limit
     * @param int    $page
     * @param string $moderationStatus
     * @param string $status
     * @param string $sortField
     * @param string $sortDirection
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getGroupVideos($limit = 10, $page = 1, $moderationStatus = "accepted",
                                   $status = "ready", $sortField = "date", $sortDirection = "desc")
    {
        $command = $this->client->getCommand('GetGroupVideos',
            array('page'=>$page,
                  'per_page'=>$limit,
                  'moderation_status'=>$moderationStatus,
                  'status'=>$status,
                  'sort_field'=>$sortField,
                  'sort_direction'=>$sortDirection));

        return $this->client->execute($command);
    }

    /**
     * Get a video's data, including embed code.
     *
     * @param int $id
     * @param int $width
     * @param int $height
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getVideo($id, $width=null, $height=null)
    {
        $commandParams = array("id"=>$id);
        if (!empty($width)) {
            $commandParams["width"] = $width;
        } else if (!empty($height)) {
            $commandParams["height"] = $height;
        }

        $command = $this->client->getCommand('GetVideo', $commandParams);

        return $this->client->execute($command);
    }

    /**
     * Publish content as non member to the group specified by $groupEmail.
     *
     * @param string $videoFile   The video file path.
     * @param string $email       The Sender's email address.
     * @param string $title       The Content title
     * @param string $description The Content's description
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishNonMemberContent($videoFile, $email="", $title="", $description="")
    {
        if (substr($videoFile, 0, 1) != "@") {
            $videoFile = "@".$videoFile;
        }

        $command = $this->client->getCommand('PublishNonMemberContent',
            array('user_email'=>$email,
                'title'=>$title,
                'description'=>$description,
                'video_file'=>$videoFile));

        return $this->client->execute($command);
    }
    
    /**
     * 
     * @param string $title
     * @param string $description
     * @param array $config
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateGroup($title, $description, $config = array())
    {
      $command = $this->client->getCommand('UpdateGroup',
            array(
                'title' => $title,
                'description' => $description,
                'config' => $config
            ));

        return $this->client->execute($command);
    }
    
    /**
     * Get infor for group associated with API key
     * 
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getGroupInfo()
    {
      $command = $this->client->getCommand('GetGroupInfo');
      return $this->client->execute($command);
    }
    
    /**
     * 
     * @param int $id
     * @param array $params
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateVideo($id, array $params = array())
    {
        $combinedParams = array_merge(array('id' => $id), $params);
        $command = $this->client->getCommand('UpdateVideo', $combinedParams);

        return $this->client->execute($command);
    }
    
    /**
     * Convenience method for moderating a video as accepted
     * 
     * Simply proxies updateVideo()
     * 
     * @param integer $id
     * @return array|\Guzzle\Http\Message\Response
     * @see self::updateVideo()
     */
    public function acceptVideo($id)
    {
      return $this->updateVideo($id, array('status' => 'accepted'));
    }
    
    /**
     * Convenience method for moderating a video as rejected
     * 
     * Simply proxies updateVideo()
     * 
     * @param integer $id
     * @return array|\Guzzle\Http\Message\Response
     * @see self::updateVideo()
     */
    public function rejectVideo($id)
    {
      return $this->updateVideo($id, array('status' => 'rejected'));
    }

    /**
     * Delete a video from the group
     * 
     * @param int $id
     * @return array|\Guzzle\Http\Message\Response
     */
    public function deleteVideo($id)
    {
        $command = $this->client->getCommand('DeleteVideo', array(
          'id' => $id
        ));

        return $this->client->execute($command);
    }
    
    /**
     * Get comments for a video
     * 
     * @param int $videoId
     * @param int $page
     * @param int $perPage
     * @return @return array|\Guzzle\Http\Message\Response
     */
    public function getVideoComments($videoId, $page = null, $perPage = null)
    {
        $commandParams = array(
            'id' => $videoId
        );

        if (null !== $page) {
            $commandParams['page'] = $page;
        }

        if (null !== $perPage) {
            $commandParams['per_page'] = $perPage;
        }
        
        $command = $this->client->getCommand('GetVideoComments', $commandParams);

        return $this->client->execute($command);
    }
}
