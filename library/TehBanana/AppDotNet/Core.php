<?php
namespace TehBanana\AppDotNet;

use TehBanana\AppDotNet\Exceptions\HttpResponseError;

/**
 * Core functionality to communicate with the app.net API
 */
class Core
{
    /**
     * app.net base API URL
     */
    private $adn_base_url = 'https://alpha-api.app.net/stream/0/';

    /**
     * app.net auth URL
     */
    private $adn_auth_url = 'https://alpha.app.net/oauth/';

    /**
     * The current app's client ID
     */
    private $app_client_id = null;

    /**
     * The current app's secret
     */
    private $app_client_secret = null;

    /**
     * The current app's redirection uri
     */
    private $app_redirect_uri = null;

    /**
     * The maximum requests allowed to app.net API
     */
    private $rate_request_limit = null;

    /**
     * The number of allowed app.net API requests left
     */
    private $rate_requests_remaining = null;

    /**
     * The time in seconds when the requests will be reset to maximum
     */
    private $rate_request_reset_time = null;

    /**
     * Currently authenticated user's access token
     */
    private $user_access_token = null;

    /**
     * Constructor
     * @param string $app_client_id The client ID for your app as provided by app.net
     * @param string $app_client_secet The client secret for your app as provided by app.net
     * @param string $app_redirect_uri The URI to redirect the client after authentication
     * @return void
     */
    public function __construct(
        $app_client_id,
        $app_client_secret,
        $app_redirect_uri
    ) {
        $this->app_client_id = $app_client_id;
        $this->app_client_secret = $app_client_secret;
        $this->app_redirect_uri = $app_redirect_uri;
    }

    /**
     * Get the base app.net API url
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->adn_base_url;
    }

    /**
     * Get the authe URL for a user to auth with your app
     * @param array $scope The scopes that you want to have access to. Includes:
     *  stream, email, write_post, follow, messages, export
     * @return string
     */
    public function getAuthUrl($scope = null)
    {
        $parameters = array(
            'client_id' => $this->app_client_id,
            'response_type' => 'code',
            'redirect_uri' => $this->app_redirect_uri
        );

        if ($scope) {
            $parameters['scope'] = implode(' ', $scope);
        }

        return $this->adn_auth_url . 'authenticate?' . http_build_query($parameters);
    }

    /**
     * Get the access token for a user.
     * @param string $access_code The code app.net provides once a user authorises your app
     * @return string
     */
    public function getAccessToken($access_code = null)
    {
        if (!$this->user_access_token && $access_code) {
            $parameters = array(
                'client_id' => $this->app_client_id,
                'client_secret' => $this->app_client_secret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->app_redirect_uri,
                'code' => $_GET['code']
            );

            $result = $this->httpPost($this->adn_auth_url . 'access_token', $parameters);

            $this->user_access_token = $result->access_token;
        }

        return $this->user_access_token;
    }

    /**
     * Set the access token for the current user
     * @param string $access_token The access token to set
     * @return void
     */
    public function setAccessToken($access_token)
    {
        $this->user_access_token = $access_token;
    }

    /**
     * Get the maximum requests allowed
     * @return int
     */
    public function getRateRequestLimit()
    {
        return $this->rate_request_limit;
    }

    /**
     * Get the requests remaining
     * @return int
     */
    public function getRequestsRemaining()
    {
        return $this->rate_requests_remaining;
    }

    /**
     * Get the request reset time
     * @return int
     */
    public function getRequestResetTime()
    {
        return $this->rate_request_reset_time;
    }

    /**
     * Parse the response from an API call to get request headers out and return
     * the content of the request
     * @param string $response The response to parse
     * @return object
     * @throws HttpResponseError
     */
    public function parseResponse($response)
    {
        $this->rate_request_limit = null;
        $this->rate_requests_remaining = null;
        $this->rate_request_reset_time = null;

        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        $headers = explode("\r\n", $headers);
        foreach ($headers as $header) {
            $header = explode(': ', $header, 2);
            
            if (count($header) < 2) {
                continue;
            }

            list($key, $value) = $header;

            switch($key) {
                case 'X-RateLimit-Remaining':
                    $this->rate_requests_remaining = $value;
                    break;
                case 'X-RateLimit-Limit':
                    $this->rate_request_limit = $value;
                    break;
                case 'X-RateLimit-Reset':
                    $this->rate_request_reset_time = $value;
                    break;
            }
        }

        $content = json_decode($body);

        if (isset($content->error)) {
            if (is_array($response->error)) {
                throw new HttpResponseError(
                    $response->error->message,
                    $response->error->code
                );
            } else {
                throw new HttpResponseError($response->error);
            }
        }

        return $content;
    }

    /**
     * Make a POST http request
     * @param string $url The URL to request
     * @param array $parameters The POST fields to set
     * @param bool $is_json If the POST requires to be sent as json
     * @return object
     */
    public function httpPost($url, $parameters, $is_json = false)
    {
        $ch = curl_init($url);
        $headers = array();
        if ($is_json) {
            $post_fields = json_encode($parameters);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($post_fields);
        } else {
            $post_fields = http_build_query($parameters);
        }
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        if ($this->user_access_token) {
            $headers[] = 'Authorization: Bearer ' . $this->user_access_token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->parseResponse($response);
    }

    /**
     * Make a GET http request
     * @param string $url The URL to request
     * @param array $parameters The GET fields to set
     * @return object
     */
    public function httpGet($url, $parameters = null)
    {
        if ($parameters) {
            $url .= http_build_query($parameters);
        }

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        if ($this->user_access_token) {
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER, 
                array('Authorization: Bearer ' . $this->user_access_token)
            );
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->parseResponse($response);
    }

    /**
     * Make a DELETE http request
     * @param string $url The URL to request
     * @param array $parameters The DELETE fields to set
     * @return object
     */
    public function httpDelete($url, $parameters)
    {
        $ch = curl_init($url);
        $post_fields = http_build_query($parameters);
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if ($this->user_access_token) {
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER, 
                array('Authorization: Bearer ' . $this->user_access_token)
            );
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->parseResponse($response);
    }

    /**
     * Parse out unwanted parameters
     * @param array $parameters The paramters to parse
     * @param array $allowed_parameters The allowed parameter keys to return
     * @return array
     */
    public function parseParameters($parameters, $allowed_parameters)
    {
        $parameters_return = array();
        foreach ($parameters as $key => $value) {
            if (in_array($key, $allowed_parameters)) {
                $parameters_return[$key] = $value;
            }
        }

        return $parameters_return;
    }
}
