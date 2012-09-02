<?php
namespace TehBanana\AppDotNet;

/**
 * Handles app.net Posts related functions
 */
class Posts
{
    /**
     * The main instance of TehBanana\AppDotNet\Core
     */
    private $adn_core = null;

    /**
     * The base URL used for api call in this class
     */
    private $base_url = null;

    /**
     * Constructor
     * @param object $adn_core The main instance of TehBanana\AppDotNet\Core
     * @return void
     */
    public function __construct($adn_core)
    {
        $this->adn_core = $adn_core;
        $this->base_url = $adn_core->getBaseUrl() . 'posts/';
    }

    /**
     * Create a post
     * Requires scope: write_post
     * @param string $text The text to post
     * @param string $reply_to The id of the post to reply to
     * @param array $annotations The annotations to set for the post
     * @return array
     */
    public function createPost($text, $reply_to = null, $annotations = null)
    {
        $parameters = array();
        $url = $this->base_url;

        $parameters['text'] = $text;

        if ($reply_to) {
            $parameters['reply_to'] = $reply_to;
        }

        if ($annotations) {
            $parameters['annotations'] = $annotations;
            $url .= '?include_annotations=1';
        }

        return $this->adn_core->httpPost($url, $parameters, true);
    }

    /**
     * Get a post
     * Requires scope: none
     * @param int $post_id The post id to get
     * @return array
     */
    public function getPost($post_id)
    {
        return $this->adn_core->httpGet($this->base_url . $post_id);
    }

    /**
     * Delete a post
     * Requires scope: unknown?
     * @param int $post_id The post id to delete
     * @return array
     */
    public function deletePost($post_id)
    {
        return $this->adn_core->httpDelete($this->base_url . $post_id);
    }

    /**
     * Get replies for a post
     * Requires scope: none
     * @param int $post_id The post id to get the replies for
     * @param array $optional_parameters An array of optional parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getPostReplies($post_id, $optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id',
                    'count',
                    'include_muted',
                    'include_deleted',
                    'include_directed_posts',
                    'include_annotations'
                )
            );
        }

        return $this->adn_core->httpGet($this->base_url . $post_id . '/replies', $optional_parameters);
    }

    /**
     * Get peronalized stream
     * Requires scope: none
     * @param array $optional_parameters An array of optional parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getPersonalStream($optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id',
                    'count',
                    'include_muted',
                    'include_deleted',
                    'include_directed_posts',
                    'include_annotations'
                )
            );
        }

        return $this->adn_core->httpGet($this->base_url . 'stream', $optional_parameters);
    }

    /**
     * Get the global stream
     * Requires scope: none
     * @param array $optional_parameters An array of optional parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getGlobalStream($optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id',
                    'count',
                    'include_muted',
                    'include_deleted',
                    'include_directed_posts',
                    'include_annotations'
                )
            );

        }

        return $this->adn_core->httpGet($this->base_url . 'stream/global', $optional_parameters);
    }

    /**
     * Get posts with a hashtag
     * Requires scope: none
     * @param string $hashtag The hashtag to search for
     * @param array $optional_parameters An array of optional parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getPostsByHashtag($hashtag, $optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id',
                    'count',
                    'include_muted',
                    'include_deleted',
                    'include_directed_posts',
                    'include_annotations'
                )
            );
        }

        return $this->adn_core->httpGet($base_url . 'tag/' . $hashtag, $optional_parameters);
    }
}
