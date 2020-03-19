<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Api/Model/SMSDelivery.php
 */

/**
 * Model class for Bronto_Api_Model_SMSDelivery level convenience methods
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Bronto_Api_Model_SMSDelivery extends Bronto_Api_Model_DeliveryAbstract
{
    const TYPE_BULK = 'bulk';
    const TYPE_WORKFLOW = 'workflow';
    const TYPE_TRANSACTION = 'transaction';
    const TYPE_TEST = 'test';

    const STATUS_SENT = 'sent';
    const STATUS_SENDING = 'sending';
    const STATUS_UNSENT = 'unsent';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_SKIPPED = 'skipped';

    /**
     * @see parent
     */
    public function __construct(array $data = array())
    {
        parent::__construct('SMSDelivery', $data);
    }

    /**
     * @see parent
     */
    public function isTransactional()
    {
        return $this->hasDeliveryType() && $this->getDeliveryType() == self::TYPE_TRANSACTION;
    }
}