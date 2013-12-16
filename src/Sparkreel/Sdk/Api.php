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
    /** @var \Sparkreel\Sdk\SparkreelClient */
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
    public function __construct($baseUrl = "https://api.sparkreel.com/v1", $options = array(), \Guzzle\Service\Client &$client = null)
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
     * @param  int                                 $limit
     * @param  int                                 $page
     * @param  string                              $moderationStatus
     * @param  string                              $status
     * @param  string                              $sortField
     * @param  string                              $sortDirection
     * @param  array                               $ids              Id's to filter videos to
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getGroupVideos($limit = 10, $page = 1, $moderationStatus = "accepted",
                                   $status = "ready", $sortField = "date",
                                   $sortDirection = "desc", $ids = array())
    {
        $command = $this->client->getCommand('GetGroupVideos',
            array('page' => $page,
                'per_page' => $limit,
                'moderation_status' => $moderationStatus,
                'status' => $status,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
                'ids' => implode(",", $ids)));

        return $this->client->execute($command);
    }

    /**
     * Get an array containing the response of the api. If successful,
     * the "videos" key will hold $limit number of videos for the requested
     * $page.
     *
     * @param $tag
     * @param  int                                 $limit
     * @param  int                                 $page
     * @param  string                              $moderationStatus
     * @param  string                              $status
     * @param  string                              $sortField
     * @param  string                              $sortDirection
     * @param  array                               $ids              Id's to filter videos to
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getGroupVideosByTeg($tag, $limit = 10, $page = 1, $moderationStatus = "accepted",
                                   $status = "ready", $sortField = "date",
                                   $sortDirection = "desc")
    {
        $command = $this->client->getCommand('GetGroupVideosByTag',
            array(
                'tag'=>$tag,
                'page' => $page,
                'per_page' => $limit,
                'moderation_status' => $moderationStatus,
                'status' => $status,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            )
        );

        return $this->client->execute($command);
    }

    /**
     * Add tags to a video, multiple comma separated tags can be
     * added at once.
     *
     * @param $tag
     * @param $videoId
     * @return array|\Guzzle\Http\Message\Response
     */
    public function addVideoTag($tag, $videoId)
    {
        $command = $this->client->getCommand('AddVideoTag',
            array(
                'tag'=>$tag,
                'id' => $videoId,
            )
        );

        return $this->client->execute($command);
    }

    /**
     * Remove a single tag from a content
     *
     * @param $tag
     * @param $videoId
     * @return array|\Guzzle\Http\Message\Response
     */
    public function removeVideoTag($tag, $videoId)
    {
        $command = $this->client->getCommand('RemoveVideoTag',
            array(
                'tag'=>$tag,
                'id' => $videoId,
            )
        );

        return $this->client->execute($command);
    }

    /**
     * Remove all tags from a content
     *
     * @param $videoId
     * @return array|\Guzzle\Http\Message\Response
     */
    public function clearVideoTags($videoId)
    {
        $command = $this->client->getCommand('ClearVideoTags',
            array(
                'id' => $videoId,
            )
        );

        return $this->client->execute($command);
    }

    /**
     * Returns all the group members
     */
    public function getGroupMembers()
    {
        $command = $this->client->getCommand('GetGroupMembers');

        return $this->client->execute($command);
    }

    /**
     * Returns all the group tags
     */
    public function getGroupTags()
    {
        $command = $this->client->getCommand('GetGroupTags');

        return $this->client->execute($command);
    }

    /**
     * Get a video's data, including embed code.
     *
     * @param  int                                 $id
     * @param  int                                 $width
     * @param  int                                 $height
     * @param  int                                 $autoplay
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getVideo($id, $width = null, $height = null, $autoplay = null)
    {
        $commandParams = array("id" => $id);
        if (!empty($width)) {
            $commandParams["width"] = $width;
        } elseif (!empty($height)) {
            $commandParams["height"] = $height;
        }

        if (!empty($autoplay)) {
            $commandParams['autoplay'] = 1;
        }

        $command = $this->client->getCommand('GetVideo', $commandParams);

        return $this->client->execute($command);
    }

    /**
     * Publish content as non member to the group specified by $groupEmail.
     *
     * @param  string                              $videoFile   The video file path.
     * @param  string                              $email       The Sender's email address.
     * @param  string                              $title       The Content title
     * @param  string                              $description The Content's description
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishNonMemberContent($videoFile, $email = "", $title = "", $description = "")
    {
        if (substr($videoFile, 0, 1) != "@") {
            $videoFile = "@" . $videoFile;
        }

        $command = $this->client->getCommand('PublishNonMemberContent',
            array('user_email' => $email,
                'title' => $title,
                'description' => $description,
                'video_file' => $videoFile));

        return $this->client->execute($command);
    }

    /**
     * Publish content as non member to the group specified by $groupEmail.
     *
     * @param  string                              $externalUrl The video url.
     * @param  string                              $email       The Sender's email address.
     * @param  string                              $title       The Content title
     * @param  string                              $description The Content's description
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishNonMemberContentExternal($externalUrl, $email = "", $title = "", $description = "")
    {
        $command = $this->client->getCommand('PublishNonMemberContent',
            array('user_email' => $email,
                'title' => $title,
                'description' => $description,
                'external_url' => $externalUrl));

        return $this->client->execute($command);
    }

    /**
     * Publish a video using an uploaded file (SparkreelVideo)
     *
     * @param  string                              $oauthAccessToken
     * @param  string                              $title
     * @param  string                              $description
     * @param  string                              $videoFile
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishMemberContentFile($oauthAccessToken, $title, $description, $videoFile)
    {
        if (substr($videoFile, 0, 1) != "@") {
            $videoFile = "@" . $videoFile;
        }

        $command = $this->client->getCommand('PublishMemberContent', array(
            'oauth_access_token' => $oauthAccessToken,
            'title' => $title,
            'description' => $description,
            'video_file' => $videoFile
        ));

        return $this->client->execute($command);
    }

    /**
     * Publish a 3rd-party video
     *
     * @param  string                              $oauthAccessToken
     * @param  string                              $title
     * @param  string                              $description
     * @param  string                              $externalUrl
     * @return array|\Guzzle\Http\Message\Response
     */
    public function publishMemberContentExternal($oauthAccessToken, $title, $description, $externalUrl)
    {
        $command = $this->client->getCommand('PublishMemberContent', array(
            'oauth_access_token' => $oauthAccessToken,
            'title' => $title,
            'description' => $description,
            'external_url' => $externalUrl
        ));

        return $this->client->execute($command);
    }

    /**
     *
     * @param  string                              $title
     * @param  string                              $description
     * @param  array                               $config
     * @param  null                                $submission
     * @param  null                                $currentTag
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateGroup($title, $description, $config = array(), $submission = null, $currentTag = null)
    {
        $command = $this->client->getCommand('UpdateGroup',
            array(
                'title' => $title,
                'description' => $description,
                'config' => $config
            ));

        if (!empty($submission)) {
            $command['submission'] = $submission;
        }

        if ($currentTag !== null) {
            $command['current_tag'] = $currentTag;
        }

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
     * Convenience method for moderating a video as accepted
     *
     * Simply proxies updateVideo()
     *
     * @param  integer                             $id
     * @return array|\Guzzle\Http\Message\Response
     * @see self::updateVideo()
     */
    public function acceptVideo($id)
    {
        return $this->updateVideo($id, array('status' => 'accepted'));
    }

    /**
     *
     * @param  int                                 $id
     * @param  array                               $params
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateVideo($id, array $params = array())
    {
        $combinedParams = array_merge(array('id' => $id), $params);
        $command = $this->client->getCommand('UpdateVideo', $combinedParams);

        return $this->client->execute($command);
    }

    /**
     * Convenience method for moderating a video as rejected
     *
     * Simply proxies updateVideo()
     *
     * @param  integer                             $id
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
     * @param  int                                 $id
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
     * @param  int                                 $videoId
     * @param  int                                 $page
     * @param  int                                 $perPage
     * @return array|\Guzzle\Http\Message\Response
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

    /**
     *
     * @param  int                                 $videoId
     * @param  string                              $oauthAccessToken
     * @param  string                              $commentText
     * @param  int                                 $replyTo
     * @return array|\Guzzle\Http\Message\Response
     */
    public function postComment($videoId, $oauthAccessToken, $commentText, $replyTo = null)
    {
        $params = array(
            'id' => $videoId,
            'oauth_access_token' => $oauthAccessToken,
            'comment_text' => $commentText
        );

        if (is_numeric($replyTo)) {
            $params['reply_to'] = $replyTo;
        }

        $command = $this->client->getCommand('PostComment', $params);

        return $this->client->execute($command);
    }

    /**
     * Get info for an external resource url
     *
     * @param $url
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getExternalProviderInfo($url)
    {
        $params = array("url" => $url);
        $command = $this->client->getCommand('GetExternalProviderInfo', $params);

        return $this->client->execute($command);
    }

    /**
     * Return a user identified either by id or by access token
     *
     * @param  int|null                            $id
     * @param  string|null                         $oauthAccessToken
     * @param  null                                $username
     * @throws \Exception
     * @return array|\Guzzle\Http\Message\Response
     */
    public function getUser($id = null, $oauthAccessToken = null, $username = null)
    {
        $params = array();
        $command = null;

        if (!empty($id)) {
            $params['id'] = $id;
            $command = $this->client->getCommand('GetUser', $params);
        } elseif (!empty($oauthAccessToken)) {
            $params['oauth_access_token'] = $oauthAccessToken;
            $command = $this->client->getCommand('GetCurrentUser', $params);
        } elseif (!empty($username)) {
            $params['username'] = $username;
            $command = $this->client->getCommand('GetUser', $params);
        } else {
            throw new \Exception("At least one of id or oauthAccessToken must be provided");
        }

        return $this->client->execute($command);
    }

    /**
     * Update the current user data
     *
     * @param $oauthAccessToken
     * @param  array                               $params
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateUser($oauthAccessToken, array $params = array())
    {
        $combinedParams = array_merge(array('oauth_access_token' => $oauthAccessToken), $params);
        $command = $this->client->getCommand('UpdateCurrentUser', $combinedParams);

        return $this->client->execute($command);
    }

    /**
     * Update or create a user avatar
     *
     * @param  string                              $oauthAccessToken
     * @param  string                              $avatarFile       File location
     * @return array|\Guzzle\Http\Message\Response
     */
    public function updateUserAvatar($oauthAccessToken, $avatarFile)
    {
        if (substr($avatarFile, 0, 1) != "@") {
            $avatarFile = "@" . $avatarFile;
        }

        $command = $this->client->getCommand('PostUserAvatar', array(
            'oauth_access_token' => $oauthAccessToken,
            'avatar_file' => $avatarFile
        ));

        return $this->client->execute($command);
    }

    public function getTopTenUsers()
    {
        $command = $this->client->getCommand('GetTopTen', array());

        return $this->client->execute($command);
    }
}
