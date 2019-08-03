<?php

class Bronto_Product_Model_Collect_Recentlyviewed extends Bronto_Product_Model_Collect_Abstract
{

    const PRODUCT_LIMIT_BUFFER = 5;

    public function collect()
    {
        $itemsRemaining = $this->getRemainingCount();
        if ($itemsRemaining <= 0) {
            return array();
        }

        /** @var Mage_Customer_Model_Customer $customer */
        $customer = $this->getRecommendation()->getCustomer();

        // It's possible that we don't have a customer
        if (!$customer) {
            return array();
        }

        /** @var Mage_Reports_Model_Event $eventReportModel */
        $eventReportModel = $this->getNewModel('reports/event');

        /** @var Mage_Reports_Model_Resource_Event_Collection $collection */
        $collection = $eventReportModel->getCollection();

        $this->configureCollectionQuery($collection, $customer->getId());

        return $this->_fillProducts($collection);
    }

    /**
     * @param Mage_Reports_Model_Resource_Event_Collection $collection (type not enforced for testing reasons)
     * @param int $customerId
     */
    protected function configureCollectionQuery($collection, $customerId)
    {
        /*
         * Add a buffer of items to lessen the likelihood that
         * downstream filtering will deliver too few results
         */
        $productLimit = $this->getRemainingCount() + self::PRODUCT_LIMIT_BUFFER;

        /** @var Varien_Db_Select $select */
        $select = $collection->getSelect();
        $select->columns(array('object_id', 'MAX(logged_at) AS logged_at'))
            ->where('event_type_id = ?', \Mage_Reports_Model_Event::EVENT_PRODUCT_VIEW)
            ->where('store_id IN(?)', array($this->getStoreId()))
            ->where('subject_id = ?', $customerId)
            ->where('subtype = ?', 0)
            ->group('object_id')
            ->limit($productLimit);

        $collection->addOrder('logged_at');
    }
}
