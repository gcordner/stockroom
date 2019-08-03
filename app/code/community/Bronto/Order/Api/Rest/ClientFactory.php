<?php

class Bronto_Order_Api_Rest_ClientFactory
{
    /**
     * @var \Bronto_Order_Api_Rest_ClientFactory
     */
    public static $instance;

    /**
     * @var \Bronto_Order_Api_Rest_Client[]
     */
    private $clients = array();

    /**
     * \Bronto_Order_Api_Rest_ClientFactory constructor.
     */
    private function __construct() {}

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param \Bronto_Order_Api_Rest_ClientFactory $clientFactory
     * @return \Bronto_Order_Api_Rest_ClientFactory
     */
    public function setInstance(\Bronto_Order_Api_Rest_ClientFactory $clientFactory)
    {
        self::$instance = $clientFactory;
        return self::$instance;
    }

    /**
     * @param string $id
     * @return \Bronto_Order_Api_Rest_Client|null
     */
    public function getByClientId($id)
    {
        return isset($this->clients[$id]) ? $this->clients[$id] : null;
    }

    /**
     * Overwrites any pre-existing clients stored in the clients array with the same client ID
     * 
     * @param string $clientId
     * @param string $clientSecret
     * @return \Bronto_Order_Api_Rest_Client
     */
    public function create($clientId, $clientSecret)
    {
        $this->clients[$clientId] = new \Bronto_Order_Api_Rest_Client($clientId, $clientSecret);
        return $this->clients[$clientId];
    }
}