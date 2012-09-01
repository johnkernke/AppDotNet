<?php
namespace TehBanana\AppDotNet;

/**
 * Handles app.net Token related functions
 */
class Token
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
        $this->base_url = $adn_core->getBaseUrl() . 'token/';
    }

    /**
     * Check the current Token
     * Requires scope: none
     * @return array
     */
    public function checkToken() {
        return $this->adn_core->httpGet($this->base_url);
    }
}
