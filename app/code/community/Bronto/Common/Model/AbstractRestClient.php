<?php

/**
 * Class Bronto_Common_Model_RestClient
 *
 * Wrapper class for \Zend_Rest_Client. This class will leverage Magento's caching mechanism to store API token values.
 *
 * Dynamic method signatures for http client request methods
 * @method \Zend_Http_Response get(string $path)
 * @method \Zend_Http_Response post(string $path)
 * @method \Zend_Http_Response put(string $path)
 * @method \Zend_Http_Response delete(string $path)
 */
class Bronto_Common_Model_AbstractRestClient extends \Zend_Rest_Client
{
    /** For authentication and regenerating refresh/access tokens */
    const BRONTO_AUTH_URI = 'https://auth.bronto.com/';
    const BRONTO_AUTH_PATH = '/oauth2/token';

    /** For all REST operations */
    const BRONTO_OPS_URI = 'https://rest.bronto.com/';
    
    const BRONTO_RESPONSE_HEADER_REASON = 'X-reason';

    const AUTH_GRANT_TYPE_ACCESS_TOKEN = 'refresh_token';
    const AUTH_GRANT_TYPE_REFRESH_TOKEN = 'client_credentials';

    const CACHE_TAG = 'bronto_cache'; // TODO: This may need to be moved in the future if we use Magento caching elsewhere
    const CACHE_KEY_PREFIX = 'bronto_token_';
    const CACHE_KEY_REFRESH = 'refresh';
    const CACHE_KEY_ACCESS = 'access';
    const CACHE_LIFETIME_REFRESH = 2592000; // 30 days
    const CACHE_LIFETIME_ACCESS = 3600; // 1 hour

    const DEFAULT_RETRY_LIMIT = 2;
    const DEFAULT_RETRY_BACKOFF_DURATION = 1;
    
    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var string */
    protected $refreshToken;

    /** @var string */
    protected $accessToken;

    /** @var int */
    protected $retryLimit;

    /** @var string authorization URI override */
    protected $authUri;

    /** @var string Operations URI override */
    protected $opsUri;

    /** @var \Bronto_Common_Helper_Data */
    protected $helper;

    /** @var bool $authenticated [false] */
    protected $authenticated = false;

    /**
     * Bronto_Common_Model_RestClient constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken [null]
     * @param string $accessToken [null]
     * @param int $retryLimit [self::DEFAULT_RETRY_LIMIT]
     * @param string $authUri [null]
     * @param string $opsUri [null]
     * @param \Bronto_Common_Helper_Data $helper [null]
     */
    public function __construct(
        $clientId,
        $clientSecret,
        $refreshToken = null,
        $accessToken = null,
        $retryLimit = null,
        $authUri = null,
        $opsUri = null,
        \Bronto_Common_Helper_Data $helper = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->setRefreshToken($refreshToken ?: $this->getCachedToken(self::CACHE_KEY_REFRESH));
        $this->setAccessToken($accessToken ?: $this->getCachedToken(self::CACHE_KEY_ACCESS));
        $this->retryLimit = $retryLimit !== null ? $retryLimit : self::DEFAULT_RETRY_LIMIT;
        $this->authUri = $authUri ?: self::BRONTO_AUTH_URI;
        $this->opsUri = $opsUri ?: self::BRONTO_OPS_URI;
        $this->helper = $helper ?: \Mage::helper('bronto_common');

        parent::__construct($this->opsUri);
    }

    /**
     * @return Bronto_Common_Model_AbstractRestClient
     */
    public function authenticate()
    {
        $problemMessage = 'A problem occurred while attempting to authenticate with the Bronto API';
        try {
            $this->authenticated = $this->refreshToken ? $this->regenAccessToken() : false;
            $this->authenticated = $this->authenticated ?: $this->regenRefreshToken();
        } catch (\Bronto_Api_Exception $bae) {
            $this->helper->writeError("{$problemMessage}: " . $bae->getMessage(), $bae->getFile());
            throw $bae;
        }

        if (!$this->authenticated) {
            throw new \Bronto_Api_Exception($problemMessage);
        }
        return $this;
    }

    /**
     * Regenerates Access Token. Defers to regenerating the Refresh Token if Refresh Token is expired/null.
     *
     * @return bool
     */
    protected function regenAccessToken()
    {
        $this->helper->writeDebug('Regenerating Bronto API Access Token');
        $authData = array(
            'grant_type' => self::AUTH_GRANT_TYPE_ACCESS_TOKEN,
            'refresh_token' => $this->refreshToken
        );
        return $this->requestAuthentication($authData);
    }

    /**
     * Regenerates and sets Refresh Token.
     *
     * Also sets Access Token.
     *
     * @return bool
     */
    protected function regenRefreshToken()
    {
        $this->helper->writeDebug('Regenerating Bronto API Refresh and Access Tokens');
        $authData = array('grant_type' => self::AUTH_GRANT_TYPE_REFRESH_TOKEN);
        return $this->requestAuthentication($authData);
    }

    /**
     * Requests authentication and sets/caches the Refresh and Access Tokens upon success
     *
     * @param [] $authData
     * @return bool
     */
    protected function requestAuthentication(array $authData)
    {
        // use a different Zend_Http_Client for authorization requests.
        $this->setUri($this->authUri);
        $opsHttpClient = $this->getHttpClient();
        $this->setHttpClient(new Zend_Http_Client());
        
        $authData['client_id'] = $this->clientId;
        $authData['client_secret'] = $this->clientSecret;
        $response = $this->restPost(self::BRONTO_AUTH_PATH, $authData);
        $success = $this->handleResponse($response);
        
        // re-set the Zend_Http_Client used to operations
        $this->setUri($this->opsUri);
        $this->setHttpClient($opsHttpClient);
        if ($success) {
            $results = json_decode($response->getBody());
            $this->setRefreshToken($results->refresh_token, true);
            $this->setAccessToken($results->access_token, true);
        }
        return $success;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
    
    /**
     * @param string $value
     * @param bool $cache [false]
     * @return Bronto_Common_Model_AbstractRestClient
     */
    public function setRefreshToken($value, $cache = false)
    {
        $this->refreshToken = $value;
        if ($cache) {
            $this->cacheToken(self::CACHE_KEY_REFRESH, $value, self::CACHE_LIFETIME_REFRESH);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $value
     * @param bool $cache [false]
     * @return Bronto_Common_Model_AbstractRestClient
     */
    public function setAccessToken($value, $cache = false)
    {
        $this->accessToken = $value;
        if ($cache) {
            $this->cacheToken(self::CACHE_KEY_ACCESS, $value, self::CACHE_LIFETIME_ACCESS);
        }
        $this->setReqHeaders();
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @param integer $lifeTime [null]
     * @return bool
     */
    protected function cacheToken($key, $value, $lifeTime = null)
    {
        $fullKey = self::CACHE_KEY_PREFIX . $key . '_' . $this->getClientId();
        return \Mage::app()->getCache()->save(serialize($value), $fullKey, array(self::CACHE_TAG), $lifeTime);
    }

    /**
     * @param $key
     * @return string|null Returns null if no token value exists for the given key
     */
    protected function getCachedToken($key)
    {
        $fullKey = self::CACHE_KEY_PREFIX . $key . '_' . $this->getClientId();
        return unserialize(\Mage::app()->getCache()->load($fullKey)) ?: null;
    }

    /**
     * Sets appropriate headers including the current access token
     */
    protected function setReqHeaders()
    {
        $httpClient = $this->getHttpClient();
        $httpClient->setConfig(array('keepalive' => true));
        $httpClient->setHeaders('Content-Type', 'application/json');
        if ($this->getAccessToken() !== null) {
            $httpClient->setHeaders('Authorization', "Bearer {$this->getAccessToken()}");
        }
        $this->setHttpClient($httpClient);
    }

    /**
     * @Override \Zend_Rest_Client::__call
     *
     * Some of this logic is adapted from \Zend_Rest_Client::__call to fit the Bronto API
     *
     * Either the method is an HTTP method which we will perform a request for or the method is the name of a request
     * parameter for which we will set aside as a part of the next HTTP request.
     *
     * @param string $method Method name
     * @param array $args Method args
     * @return \Zend_Http_Response|Bronto_Common_Model_AbstractRestClient
     */
    public function __call($method, $args)
    {
        $methods = array('post', 'get', 'delete', 'put');
        if (in_array(strtolower($method), $methods)) {
            $path = count($args) > 0 ? $args[0] : '/';
            $response = null;
            $attempts = 0;
            $successful = false;

            do {
                try {
                    $this->setNoReset(true);
                    $response = $this->{'rest' . $method}($path, $this->_data);
                    $successful = $this->handleResponse($response);
                } catch (\Exception $e) {
                    $lastException = new \Bronto_Api_Exception($e->getMessage(), $e->getCode(), $e);
                    $errMessage = 'Bronto API request error. ';
                    if ($attempts >= $this->retryLimit) {
                        $this->getHttpClient()->resetParameters();
                        throw new \Bronto_Api_Exception($errMessage . 'Retry limit reached.', $e->getCode(), $lastException);
                    }
                    if (!$lastException->isRecoverable()) {
                        $this->getHttpClient()->resetParameters();
                        $reason = $this->getErrorResponseReason() ?: null;
                        throw new \Bronto_Api_Exception($errMessage . $reason, $e->getCode(), $e);
                    } elseif ($lastException->isTokenInvalid()) {
                        $this->authenticate();
                    } elseif ($lastException->isNetworkRelated()) {
                        $backoff = self::DEFAULT_RETRY_BACKOFF_DURATION * $attempts;
                        sleep($backoff);
                    }
                }
                $attempts++;
            } while (!$successful);

            //Initializes for next Rest method.
            $this->_data = array();
            $this->getHttpClient()->resetParameters();
            
            return $response;
        } else {
            if (sizeof($args) > 0) {
                $this->_data[$method] = $args[0];
            }
            return $this;
        }
    }

    /**
     * @param \Bronto_Common_Api_Rest_AbstractDto $dto
     * @return self
     */
    public function setPostBodyFromDto(\Bronto_Common_Api_Rest_AbstractDto $dto)
    {
        $httpClient = $this->getHttpClient();
        $httpClient->setRawData($dto->toJSON(), 'application/json');
        $this->setHttpClient($httpClient);
        return $this;
    }

    /**
     * Accepts an array of query params or the key as the first argument and value as the second parameter
     *
     * @param array|string $key
     * @param mixed|null $value [null]
     * @return self
     */
    public function addQueryParam($key, $value = null)
    {
        $httpClient = $this->getHttpClient();
        $httpClient->setParameterGet($key, $value);
        $this->setHttpClient($httpClient);
        return $this;
    }

    /**
     * @param Zend_Http_Response [null] $response
     *  Responses have the possibility of being null if there was a failure in prepping the request.
     *
     * @return bool
     */
    protected function handleResponse(\Zend_Http_Response $response = null)
    {
        if ($response === null) {
            throw new \Bronto_Api_Exception('A problem occurred while generating the request');
        } elseif ($response->getStatus() < 200 || $response->getStatus() >= 300) {
            throw new \Bronto_Api_Exception($this->getErrorResponseReason(), $response->getStatus());
        }

        return true;
    }

    /**
     * Gets the reason for the error. First from x-reason header, which is expected to be populated,
     * then defaults to the message body
     *
     * @return string
     */
    public function getErrorResponseReason()
    {
        $response = $this->getHttpClient()->getLastResponse();
        $headers = $response->getHeaders();
        return (isset($headers[self::BRONTO_RESPONSE_HEADER_REASON]))
            ? $headers[self::BRONTO_RESPONSE_HEADER_REASON]
            : $response->getMessage();
    }

    /**
     * Expects header to be of the form:
     *  ServiceExceptionMessage{siteId=00001, statusCode=400, reason=Reason text here}
     *
     * @param string $header
     * @return array
     */
    protected function parseReasonHeader($header)
    {
        $tupleStrings = explode(', ', trim(substr($header, strpos($header, '{')), " {}"));
        $headerValues = array();
        foreach ($tupleStrings as $tuple) {
            $explodedTuple = explode('=', $tuple);
            if (count($explodedTuple) == 2) {
                $headerValues[$explodedTuple[0]] = $explodedTuple[1];
            }
        }
        return $headerValues;
    }
}