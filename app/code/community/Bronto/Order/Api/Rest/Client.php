<?php

/**
 *
 * Class Bronto_Order_Api_Rest_Client
 */
class Bronto_Order_Api_Rest_Client extends Bronto_Common_Model_AbstractRestClient
{
    const API_PATH = '/orders';

    const VALID_RESPONSE_CODE_GET = 200;
    const VALID_RESPONSE_CODE_UPDATE = 200;
    const VALID_RESPONSE_CODE_ADD = 201;
    const VALID_RESPONSE_CODE_DELETED = 204;

    const INVALID_RESPONSE_CODE_BAD_REQUEST = 400;
    const INVALID_RESPONSE_CODE_NOT_FOUND = 404;
    
    protected $defaultAddOrUpdateParams = array(
        'createContact' => 'true',
        'triggerEvents' => 'true',
        'force' => 'true',
        'ignoreInvalidTid' => 'true'
    );

    /**
     * Adds an order to Bronto
     *
     * @param Bronto_Order_Api_Rest_Dto $orderDto
     * @param []|null $uriParams [null]
     * @return Bronto_Order_Api_Rest_Dto
     * @throws \Bronto_Api_Exception if add was not successful
     */
    public function addOrder(\Bronto_Order_Api_Rest_Dto $orderDto, $uriParams = null)
    {
        $this->addQueryParam($uriParams !== null ? $uriParams : $this->defaultAddOrUpdateParams);
        $this->setPostBodyFromDto($orderDto);

        $response = $this->post('/orders');
        $this->handleResponse($response);

        return \Bronto_Order_Api_Rest_DtoFactory::getInstance()->buildFromResponse($response);
    }

    /**
     * @param Bronto_Order_Api_Rest_Dto $orderDto
     * @param []|null $uriParams [null]
     * @return Bronto_Order_Api_Rest_Dto
     * @throws \Bronto_Api_Exception if update was not successful
     */
    public function updateOrder(\Bronto_Order_Api_Rest_Dto $orderDto, $uriParams = null)
    {
        $this->addQueryParam($uriParams !== null ? $uriParams : $this->defaultAddOrUpdateParams);
        $this->setPostBodyFromDto($orderDto);

        $response = $this->post(self::API_PATH . "/customerOrderId/{$orderDto->getCustomerOrderId()}");
        $this->handleResponse($response);
        
        return \Bronto_Order_Api_Rest_DtoFactory::getInstance()->buildFromResponse($response);
    }

    /**
     * Attempts and update and if the order doesn't exists then performs and add
     * 
     * @param Bronto_Order_Api_Rest_Dto $orderDto
     * @return Bronto_Order_Api_Rest_Dto
     * @throws \Bronto_Api_Exception if import was not successful
     */
    public function addOrUpdateOrder(\Bronto_Order_Api_Rest_Dto $orderDto)
    {
        $responseDto = null;
        try {
            $responseDto = $this->updateOrder($orderDto);
        } catch (\Bronto_Api_Exception $bae) {
            $this->helper->writeDebug(
                "Update error for order number {$orderDto->getCustomerOrderId()}:\n " . $bae->getMessage()
            );

            if ($bae->getCode() != self::INVALID_RESPONSE_CODE_NOT_FOUND) {
                throw $bae;
            }
            // Order not found. Attempt to add instead.
            $responseDto = $this->addOrder($orderDto);
        }
        return $responseDto;
    }

    /**
     * @param int $id
     * @return Bronto_Order_Api_Rest_Dto|null
     * @throws \Bronto_Api_Exception if get was not successful
     */
    public function getOrder($id)
    {
        $response = $this->get(self::API_PATH . "/customerOrderId/{$id}");
        if ($response->getStatus() == self::INVALID_RESPONSE_CODE_NOT_FOUND) {
            return null;
        }
        $this->handleResponse($response);

        return Bronto_Order_Api_Rest_DtoFactory::getInstance()->buildFromResponse($response);
    }

    /**
     * @param string $id
     * @throws \Bronto_Api_Exception if delete was not successful
     */
    public function deleteOrder($id)
    {
        $response = $this->delete(self::API_PATH . "/customerOrderId/{$id}");
        if ($response->getStatus() != self::VALID_RESPONSE_CODE_DELETED) {
            $message = $this->getErrorResponseReason() ?: 'Unknown Error';
            throw new Bronto_Api_Exception("Unexpected response while deleting order ID {$id}: {$message}", $response->getStatus());
        }
    }
}