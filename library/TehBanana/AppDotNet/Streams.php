<?php
namespace TehBanana\AppDotNet;

use TehBanana\AppDotNet\NotImplimentedError;

/**
 * Handles app.net Steams related functions
 */
class Steams
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
        throw new NotImplimentedError('Steams are currently not implmented by app.net');
        
        $this->adn_core = $adn_core;
        $this->base_url = $adn_core->getBaseUrl() . 'streams/';
    }
}
