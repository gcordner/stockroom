<?php
/**
 * Rocketgate Gateway
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @author     Jason Burns <jason@rocketgate.com>
 */

// Use class_exists() to remove errors when using Compilation
if (!class_exists('GatewayService', false)) {
    require_once 'Sdk/GatewayService.php';
}

// Use class_exists() to remove errors when using Compilation
if (!class_exists('GatewayRequest', false)) {
    require_once 'Sdk/GatewayRequest.php';
}

// Use class_exists() to remove errors when using Compilation
if (!class_exists('GatewayResponse', false)) {
    require_once 'Sdk/GatewayResponse.php';
}

/**
 * Options Source Model
 *
 * @category   RocketGate
 * @package    RocketGate_RocketGateway
 * @subpackage Model
 */
class RocketGate_RocketGateway_Model_RocketGateway extends Mage_Payment_Model_Method_Cc
{
    const RESPONSE_CODE_APPROVED            = 0; // Success
    const RESPONSE_CODE_DECLINED            = 1; // Bank Decline
    const RESPONSE_CODE_SCRUB               = 2; // Scrubbing Decline
    const RESPONSE_CODE_ERROR               = 3; // System Error
    const RESPONSE_CODE_REQUEST             = 4; // Rejected: Missing Fields / Field Validation
    
    protected $_code                        = 'rocketgateway';

    /**
     * Availability options
     */
    protected $_isGateway                   = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;
    protected $_canCapturePartial           = true;
    protected $_canRefund                   = true;
    protected $_canRefundInvoicePartial     = true;
    protected $_canVoid                     = true;
    protected $_canUseInternal              = true;
    protected $_canUseCheckout              = true;
    protected $_canUseForMultishipping      = true;
    protected $_isInitializeNeeded          = false;
    protected $_canSaveCc                   = false;

    /**
     * Fields that should be replaced in debug with '***'
     *
     * @var array
     */
    protected $_debugReplacePrivateDataKeys = array(
        'merchantPassword', 'cardNo', 'cvv2'
    );

    /**
     * @var GatewayService
     */
    private $_service;

    /**
     * @var GatewayRequest
     */
    private $_request;

    /**
     * @var GatewayResponse
     */
    private $_response;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_request  = new GatewayRequest();
        $this->_response = new GatewayResponse();
        $this->_service  = new GatewayService();
    }

    /**
     * Send authorize request to gateway
     *
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        parent::authorize($payment, $amount);       
        $error = false;
        $order = $payment->getOrder();

        if ($amount > 0) {

            $payment->setAmount($amount);

            //
            // Build our Rocketgate Request
            //
            $this->_setBaseRequest($payment);
            $this->_setDebitRequest($payment);

            // Setup configured risk parameters
            $this->_setupRiskParams($payment);

            //
            // Perform the Rocketgate Request
            //
            $this->_beforePerform($payment);
            $this->_service->PerformAuthOnly($this->_request, $this->_response);
            $this->_afterPerform($payment);

            // Adding 1 to the response code since Rocketgate returns 0 on success and not 1
            $payment->setCcApproval($this->_response->Get(GatewayResponse::RESPONSE_CODE()) + 1);
            $payment->setCcAvsStatus($this->_response->Get(GatewayResponse::AVS_RESPONSE()));
            $payment->setCcCidStatus($this->_response->Get(GatewayResponse::CVV2_CODE()));
            $payment->setCcTransId($this->_response->Get(GatewayResponse::TRANSACT_ID()));

            switch ($this->_response->Get(GatewayResponse::RESPONSE_CODE())) {

                case self::RESPONSE_CODE_APPROVED:
                    $payment->setRocketgateCardhash($this->_response->Get(GatewayResponse::CARD_HASH()));
                    $payment->setStatus(self::STATUS_APPROVED);
                    break;

                default:
                    $error = $this->_getRocketgateResponseMessage();
                    $payment->setStatus(self::STATUS_DECLINED);
                    break;

            }

        } else {

            $error = Mage::helper('rocketgateway')->__('Invalid amount for authorization.');

        }

        if ($error !== false) {
            Mage::throwException($error);
        }
        
        return $this;
    }

    /**
     * Send capture request to gateway
     *
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    public function capture(Varien_Object $payment, $amount)
    {
        parent::capture($payment, $amount);      
        $error = false;

        $payment->setAmount($amount);

        //
        // Build our Rocketgate Request
        //
        $this->_setBaseRequest($payment);

        //
        // Perform the Rocketgate Request
        //
        $this->_beforePerform($payment);

        if ($payment->getCcTransId()) {
            //
            // If previous transaction then this is a request to capture/settle a previous auth.
            //
            $this->_request->Set(GatewayRequest::TRANSACT_ID(), $payment->getCcTransId());

            $this->_service->PerformTicket($this->_request, $this->_response);

        } else {
            //
			// Build up Purchase Request Params
            //
            $this->_setDebitRequest($payment);

            //
            // Peform the purchase (AUTH_CAPTURE) request. Set risk parameters similar to authorize() function.
            //
            // Setup configured risk parameters
            $this->_setupRiskParams($payment);

            $this->_service->PerformPurchase($this->_request, $this->_response);

        }

        $this->_afterPerform($payment);

        // Adding 1 to the response code since Rocketgate returns 0 on success and not 1
        $payment->setCcApproval($this->_response->Get(GatewayResponse::RESPONSE_CODE()) + 1);
        $payment->setCcAvsStatus($this->_response->Get(GatewayResponse::AVS_RESPONSE()));
        $payment->setCcCidStatus($this->_response->Get(GatewayResponse::CVV2_CODE()));
        $payment->setCcTransId($this->_response->Get(GatewayResponse::TRANSACT_ID()));

        switch ($this->_response->Get(GatewayResponse::RESPONSE_CODE())) {

            case self::RESPONSE_CODE_APPROVED:
                $payment->setRocketgateCardhash($this->_response->Get(GatewayResponse::CARD_HASH()));
                $payment->setCcTransId($this->_response->Get(GatewayResponse::TRANSACT_ID()));
                $payment->setStatus(self::STATUS_APPROVED);
                break;

            default:
                $error = $this->_getRocketgateResponseMessage();
                $payment->setStatus(self::STATUS_DECLINED);
                break;

        }

        if ($error !== false) {
            Mage::throwException($error);
        }

        return $this;
    }

    /**
     * Send void request to gateway
     *
     * @param Varien_Object $payment
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    public function void(Varien_Object $payment)
    {
        $error = false;

        if ($payment->getVoidTransactionId()) {

            //
            // Build our Rocketgate Request
            //
            $this->_setBaseRequest($payment);
            $this->_request->Set(GatewayRequest::TRANSACT_ID(), $payment->getVoidTransactionId());

            //
            // Perform the Rocketgate Request
            //
            $this->_beforePerform($payment);
            $this->_service->PerformVoid($this->_request, $this->_response);
            $this->_afterPerform($payment);

            switch ($this->_response->Get(GatewayResponse::RESPONSE_CODE())) {

                case self::RESPONSE_CODE_APPROVED:
                    $payment->setStatus(self::STATUS_SUCCESS);
                    break;

                default:
                    $error = $this->_getRocketgateResponseMessage();
                    break;

            }

        } else {

            $error = Mage::helper('rocketgateway')->__('Invalid transaction id');

        }

        if ($error !== false) {
            Mage::throwException($error);
        }

        return $this;
    }

    /**
     * Send refund request to gateway
     *
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $error = false;

        if ($payment->getRefundTransactionId() && $amount > 0) {

            $payment->setAmount($amount);

            //
            // Build our Rocketgate Request
            //
            $this->_setBaseRequest($payment);
            $this->_request->Set(GatewayRequest::TRANSACT_ID(), $payment->getRefundTransactionId());

            //
            // Perform the Rocketgate Request
            //
            $this->_beforePerform($payment);
            $this->_service->PerformCredit($this->_request, $this->_response);
            $this->_afterPerform($payment);

            switch ($this->_response->Get(GatewayResponse::RESPONSE_CODE())) {

                case self::RESPONSE_CODE_APPROVED:
                    $payment->setStatus(self::STATUS_SUCCESS);
                    break;

                default:
                    $error = $this->_getRocketgateResponseMessage();
                    break;

            }

        } else if (!$payment->getRefundTransactionId()) {

            $error = Mage::helper('rocketgateway')->__('Invalid transaction id');

        } else {

            $error = Mage::helper('rocketgateway')->__('Invalid refund amount');

        }

        if ($error !== false) {
            Mage::throwException($error);
        }

        return $this;
    }

    /**
     * Called before any Rocketgate "Perform" method
     *
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    protected function _beforePerform(Varien_Object $payment)
    {
        return $this;
    }

    /**
     * Called after any Rocketgate "Perform" method
     *
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    protected function _afterPerform(Varien_Object $payment)
    {
        // Set our last transaction Id
        $payment->setLastTransId($this->_response->Get(GatewayResponse::TRANSACT_ID()));

        // Output to our debug if necessary
        if ($this->getConfigData('debug')) {

            // Save request
            $requestDebug = clone $this->_request;

            foreach ($this->_debugReplacePrivateDataKeys as $key) {
                if (isset($requestDebug->params[$key])) {
                    $requestDebug->params[$key] = '****';
                }
            }

            // Save response
            $responseDebug = clone $this->_response;

            $debug = Mage::getModel('rocketgateway/api_debug')
                ->setRequestSerialized(serialize($requestDebug))
                ->setResultSerialized(serialize($responseDebug))
                ->save();

        }

        return $this;
    }

    /**
     * Build the Rocketgate Request
     *
     * @param Varien_Object $payment
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    protected function _setBaseRequest(Varien_Object $payment)
    {
        // Setup Merchant information
        $this->_request->Set(GatewayRequest::MERCHANT_ID(),       $this->getConfigData('merchant_id'));
        $this->_request->Set(GatewayRequest::MERCHANT_PASSWORD(), $this->getConfigData('merchant_password'));

        // voids and credits don't require passing AMOUNT.
        if ($payment->getAmount()){
            $this->_request->Set(GatewayRequest::AMOUNT(), $payment->getAmount());
        }

        // Are we in test mode?
        if ($this->getConfigData('test')) {
            $this->_service->SetTestMode(true);
        }

        return $this;
    }

    /**
     * Add parameters for debit requests to the Rocketgate Request
     *
     * @param Varien_Object $payment
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    protected function _setDebitRequest(Varien_Object $payment)
    {

        // Pull order
        $order = $payment->getOrder();
        $this->setStore($order->getStoreId());

        if ($order && $order->getIncrementId()) {

            $this->_request->Set(GatewayRequest::MERCHANT_SITE_ID(),     $order->getStoreId());
            $this->_request->Set(GatewayRequest::MERCHANT_INVOICE_ID(),  $order->getIncrementId());

            if ($order->getBillingAddress()->getCustomerId()) {
                $this->_request->Set(GatewayRequest::MERCHANT_CUSTOMER_ID(), $order->getBillingAddress()->getCustomerId());
            //
		 // Don't create customer record at rocketgate if customer doesn't want it.
		 } else {
               $this->_request->Set(GatewayRequest::MERCHANT_CUSTOMER_ID(), ('guest_' .time() ));
            }
        }

        if (!empty($order)) {

            $billing = $order->getBillingAddress();

            if (!empty($billing)) {

                $this->_request->Set(GatewayRequest::CUSTOMER_FIRSTNAME(),   $billing->getFirstname());
                $this->_request->Set(GatewayRequest::CUSTOMER_LASTNAME(),    $billing->getLastname());
                $this->_request->Set(GatewayRequest::CUSTOMER_PHONE_NO(),    $billing->getTelephone());
                $this->_request->Set(GatewayRequest::BILLING_ADDRESS(),      $billing->getStreet(1));
                $this->_request->Set(GatewayRequest::BILLING_CITY(),         $billing->getCity());
                $this->_request->Set(GatewayRequest::BILLING_STATE(),        $billing->getRegion());
                $this->_request->Set(GatewayRequest::BILLING_ZIPCODE(),      $billing->getPostcode());
                $this->_request->Set(GatewayRequest::BILLING_COUNTRY(),      $billing->getCountry());

            }

            if ($order->getIncrementId()) {
                $this->_request->Set(GatewayRequest::EMAIL(),     $order->getCustomerEmail());
                $this->_request->Set(GatewayRequest::IPADDRESS(), $order->getRemoteIp());
                $this->_request->Set(GatewayRequest::CURRENCY(),  $order->getBaseCurrencyCode());
            }

        }

        // If we have a cardhash, pass it
        if ($payment->getRocketgateCardhash()) {

            $this->_request->Set(GatewayRequest::CARD_HASH(), $payment->getRocketgateCardhash());

        } else if ($payment->getCcNumber()) {

            $this->_request->Set(GatewayRequest::CARDNO(),       $payment->getCcNumber());
            $this->_request->Set(GatewayRequest::EXPIRE_MONTH(), $payment->getCcExpMonth());
            $this->_request->Set(GatewayRequest::EXPIRE_YEAR(),  $payment->getCcExpYear());
            $this->_request->Set(GatewayRequest::CVV2(),         $payment->getCcCid());

        }

        return $this;
    }


    /**
     * Helper function that returns our Rocketgate response message
     *
     * @return RocketGate_RocketGateway_Model_RocketGateway
     */
    private function _setupRiskParams(Varien_Object $payment)
    {
        $order = $payment->getOrder();

        //
        // Scrub/CVV2/AVS
        //
        $this->_request->Set(GatewayRequest::SCRUB(),      $this->getConfigData('scrubcheck'));
        $this->_request->Set(GatewayRequest::CVV2_CHECK(), $this->getConfigData('cvvcheck'));
        $this->_request->Set(GatewayRequest::AVS_CHECK(),  $this->getConfigData('avscheck'));

        // Check our IP whitelist
        $whitelist = explode(',', $this->getConfigData('ip_whitelist'));
        if (in_array($order->getRemoteIp(), $whitelist) || $this->getConfigData('test')) {
            $this->_request->Set(GatewayRequest::SCRUB(), $this->getConfigData('scrubcheck') ? 'IGNORE' : 'FALSE');
        }

        return $this;
    }

    /**
     * Helper function that returns our Rocketgate response message
     *
     * @return string
     */
    private function _getRocketgateResponseMessage()
    {
        $reasonCode   = $this->_response->Get(GatewayResponse::REASON_CODE());
        $responseCode = $this->_response->Get(GatewayResponse::RESPONSE_CODE());

        switch ($responseCode) {

            case self::RESPONSE_CODE_APPROVED:
                $message = Mage::helper('rocketgateway')->__('Success');
                break;

            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_SCRUB:
                $message = Mage::helper('rocketgateway')->__('Your transaction was Declined');
                break;

            case self::RESPONSE_CODE_ERROR:
                $message = Mage::helper('rocketgateway')->__('System Error. Your account has not been charged.') . ' - Reason: ' . $reasonCode;
                break;

            case self::RESPONSE_CODE_REQUEST:
                $message = Mage::helper('rocketgateway')->__('Rejected: Missing Fields / Field Validation.') . ' - Reason: ' . $reasonCode;
                break;

            default:
                $message = Mage::helper('rocketgateway')->__('Unknown Response Code') . ' - Reason: ' . $reasonCode;
                break;

        }

        return $message;
    }
  
}
