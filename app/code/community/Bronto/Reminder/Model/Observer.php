<?php

/**
 * @package   Bronto\Reminder
 * @copyright 2011-2013 Bronto Software, Inc.
 */
class Bronto_Reminder_Model_Observer
{
    const NOTICE_IDENTIFIER = 'bronto_reminder';

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return mixed
     */
    public function checkBrontoRequirements(Varien_Event_Observer $observer)
    {
        if (!Mage::getSingleton('admin/session')->isLoggedIn()) {
            return;
        }

        // Verify Requirements
        if (!Mage::helper(self::NOTICE_IDENTIFIER)->varifyRequirements(self::NOTICE_IDENTIFIER, array('soap', 'openssl'))) {
            return;
        }
    }

    /**
     * Observes module becoming enabled and displays message warning user to configure settings
     *
     * @param Varien_Event_Observer $observer
     */
    public function watchEnableAction(Varien_Event_Observer $observer)
    {
        Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('bronto_reminder')->__(Mage::helper('bronto_reminder')->getModuleEnabledText()));
    }

    /**
     * Include auto coupon type
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Bronto_Reminder_Model_Observer
     */
    public function getCouponTypes($observer)
    {
        if ($transport = $observer->getEvent()->getTransport()) {
            $transport->setIsCouponTypeAutoVisible(true);
        }

        return $this;
    }

    /**
     * Add custom comment after coupon type field
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Bronto_Reminder_Model_Observer
     */
    public function updatePromoQuoteTabMainForm($observer)
    {
        $form = $observer->getEvent()->getForm();
        if (!$form) {
            return $this;
        }

        if ($fieldset = $form->getElements()->searchById('base_fieldset')) {
            if ($couponTypeFiled = $fieldset->getElements()->searchById('coupon_type')) {
                $couponTypeFiled->setNote(Mage::helper('bronto_reminder')->__('Coupons can be auto-generated by reminder promotion rules.'));
            }
        }

        return $this;
    }

    /**
     * Send Scheduled Notifications
     *
     * @param bool $brontoCron
     *
     * @return $this|string
     */
    public function scheduledNotification($brontoCron = false)
    {
        Mage::helper('bronto_reminder')->writeDebug('scheduledNotification() triggered...');

        // Only allow cron to run if isset to use mage cron or is coming from bronto cron
        if (Mage::helper('bronto_reminder')->canUseMageCron() || $brontoCron) {
            if (Mage::helper('bronto_reminder')->isEnabled()) {
                $result = Mage::getModel('bronto_reminder/rule')->sendReminderEmails();

                return $result;
            } else {
                return 'Bronto_Reminder module is not enabled.';
            }
        }

        Mage::helper('bronto_reminder')->writeDebug('Done!');

        return $this;
    }

    /**
     * If a Quote/Wishlist becomes inactive/deleted/checked-out/converted,
     * remove from bronto_reminder_rule_coupon
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Varien_Event_Observer
     */
    public function updateReminderQueue(Varien_Event_Observer $observer)
    {
        $object      = $observer->getEvent()->getDataObject();
        $filterField = false;
        $filterValue = false;

        if ($object instanceof Mage_Wishlist_Model_Wishlist) {
            $wishlist   = $object;
            $collection = $wishlist->getItemCollection();

            if (0 === $collection->count()) {
                $filterField = 'wishlist_id';
                $filterValue = $wishlist->getId();
            }
        } elseif ($object instanceof Mage_Sales_Model_Quote) {
            $quote = $object;

            if (0 === $quote->getIsActive() || 0 === $quote->getItemsCount()) {
                $filterField = 'quote_id';
                $filterValue = $quote->getId();
            }
        }

        if ($filterField && $filterValue) {
            // Quote/Wishlist is not active, so remove from queue if exists
            Mage::getModel('bronto_reminder/rule')
                ->removeFromReminders($filterField, $filterValue);
        }

        return $observer;
    }
}
