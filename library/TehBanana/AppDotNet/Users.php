<?php
namespace TehBanana\AppDotNet;

/**
 * Handles app.net Users related functions
 */
class Users
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
        $this->base_url = $adn_core->getBaseUrl() . 'users/';
    }

    /**
     * Get a users information
     * Requires scope: none
     * @param int|string $user_id The user id number or the @username to get information
     * @return array
     */
    public function getUser($user_id = 'me')
    {
        return $this->adn_core->httpGet($this->base_url . $user_id);
    }

    /**
     * Follow a user for the currently authenticated user
     * Requires scope: follow
     * @param int|string $user_id The user id number or the @username to follow
     * @return array
     */
    public function followUser($user_id)
    {
        return $this->adn_core->httpPost($this->base_url . $user_id . '/follow');
    }

    /**
     * Unfollow a user for the currently authenticated user
     * Requires scope: follow
     * @param int|string $user_id The user id number or the @username to unfollow
     * @return array
     */
    public function unfollowUser($user_id)
    {
        return $this->adn_core->httpDelete($this->base_url . $user_id . '/unfollow');
    }

    /**
     * Get a list of people that a user is following
     * Requires scope: none
     * @param int|string $user_id The user id number or the @username to get following
     * @param array $optional_parameters An array of optional parameters. Requires
     *  follow_pagination migration to be enabled Allowed keys:
     *  since_id, before_id
     * @return array
     */
    public function getFollowing($user_id = 'me', $optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id'
                )
            );
        }

        return $this->adn_core->httpGet($this->base_url . $user_id . '/following', $optional_parameters);
    }

    /**
     * Get a list of people that are following a user
     * Requires scope: none
     * @param int|string $user_id The user id number or the @username to get followers
     * @param array $optional_parameters An array of optional parameters. Requires
     *  follow_pagination migration to be enabled Allowed keys:
     *  since_id, before_id
     * @return array
     */
    public function getFollowers($user_id = 'me', $optional_parameters = array())
    {
        if (!empty($optional_parameters)) {
            $optional_parameters = $this->adn_core->parseParameters(
                $optional_parameters,
                array(
                    'since_id',
                    'before_id'
                )
            );
        }

        return $this->adn_core->httpGet($this->base_url . $user_id . '/followers', $optional_parameters);
    }

    /**
     * Mute a user
     * Requires scope: follow
     * @param int|string $user_id The user id number or the @username to mute
     * @return array
     */
    public function muteUser($user_id)
    {
        return $this->adn_core->httpPost($this->base_url . $user_id . '/mute');
    }

    /**
     * Unmute a user
     * Requires scope: follow
     * @param int|string $user_id The user id number or the @username to unmute
     * @return array
     */
    public function unmuteUser($user_id)
    {
        return $this->adn_core->httpDelete($this->base_url . $user_id . '/unmute');
    }

    /**
     * Get a list of muted users for the currently authenticated user
     * Requires scope: none
     * @return array
     */
    public function getMuted()
    {
        return $this->adn_core->httpGet($this->base_url . '/me/muted');
    }

    /**
     * Get posts by a user
     * Requires scope: none
     * @param int|string $user_id The user id number or the @username to get posts of
     * @param array $optional_parameters An array of option parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getPosts($user_id = 'me', $optional_parameters = array())
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

        return $this->adn_core->httpGet($this->base_url . $user_id . '/posts', $optional_parameters);
    }

    /**
     * Get mentions for a user
     * Requires scope: none
     * @param int|string $user_id The user id number or the @user to get the mentions of
     * @param array $optional_parameters An array of option parameters. Allowed keys:
     *  since_id, before_id, count, include_muted, include_deleted, include_directed_posts, include_annotations
     * @return array
     */
    public function getMentions($user_id = 'me', $optional_parameters = array())
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

        return $this->adn_core->httpGet($this->base_url . $user_id . '/mentions', $optional_parameters);
    }
}
