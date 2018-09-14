<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 10:56
 */

namespace DangVanDiep\Platform\Drivers;

use GuzzleHttp\Client;
use InvalidArgumentException;

abstract class AbstractDriver
{
    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * The custom Guzzle configuration options.
     *
     * @var array
     */
    protected $guzzle = [];

    /**
     * The appkey
     *
     * @var string
     */
    protected $appkey;

    /**
     * The config of driver URLs
     *
     * @var array
     */
    protected $config;

    /**
     * The default queries append to url
     *
     * @var array
     */
    protected $query;

    /**
     * The error message
     *
     * @var string
     */
    public $error;

    /**
     * The user's access token.
     *
     * @var string
     */
    public $userToken;

    /**
     * Create a new provider instance.
     *
     * @param string $appkey
     *
     * @return void
     */
    public function __construct($appkey)
    {
        $this->appkey = $appkey;
        $this->error = null;
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string $token
     *
     * @return array
     */
    abstract protected function getUserByToken($token);

    /**
     * Map the raw user array to a Platform User instance.
     *
     * @param  array $user
     *
     * @return \DangVanDiep\Platform\User
     */
    abstract protected function mapUserToObject(array $user);

    /**
     * Get the default queries append on all API
     *
     * @return array
     */
    public function getDefaultQueries()
    {
        return $this->query;
    }

    /**
     * Get the API URL for the provider.
     *
     * @param string $name
     * @param array  $query
     *
     * @return string
     */
    protected function buildApiUrl($name, $query = [])
    {
        if (empty($this->config[$name])) {
            throw new InvalidArgumentException("Api [$name] not exists");
        }

        $query = array_merge($query, $this->getDefaultQueries());

        return $this->config['base_url'] . $this->config[$name] . '?' . http_build_query($query);
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle);
        }
        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param  \GuzzleHttp\Client $client
     *
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Help you to make post request to platform
     *
     * @param string   $apiName
     * @param array    $params
     * @param array    $errors
     * @param \Closure $success
     * @param array    $query
     * @param \Closure $successCondition
     *
     * @return mixed
     */
    public function makeRequest($apiName, $params, $errors, $success, $query = [], $successCondition = null)
    {
        $url = $this->buildApiUrl($apiName, $query);

        $response = $this->getHttpClient()->post($url, [
            'form_params' => $params,
        ]);

        $result = json_decode($response->getBody(), true);

        if (empty($result)) {
            $this->error = __("Can't connect to platform");

            return $this;
        }

        if (is_callable($successCondition)) {
            if ($successCondition($result)) {
                return $success($result);
            }
        } elseif ($result['status'] == 0) {
            return $success($result);
        }

        $this->error = isset($errors[$result['status']]) ? $errors[$result['status']] : $result['message'];

        return $this;
    }

    /**
     * Get a instance of the user token.
     *
     * @return string
     */
    public function getUserToken()
    {
        return $this->userToken;
    }

    /**
     * Set the user token instance.
     *
     * @param string $token
     */
    public function setUserToken(string $token)
    {
        if (!isset($this->userToken) && !empty($token)) {
            $this->userToken = $token;
        }
    }

    /**
     * Get the User instance for the authenticated user.
     *
     * @return \DangVanDiep\Platform\User
     */
    public function user()
    {
        return $this->mapUserToObject($this->getUserByToken($this->getUserToken()));
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Check request is pass or fail
     *
     * @return bool
     */
    public function failed()
    {
        if (empty($this->error)) {
            return false;
        }

        return true;
    }
}
