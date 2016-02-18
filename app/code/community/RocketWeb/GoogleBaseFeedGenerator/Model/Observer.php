<?php

/**
 * RocketWeb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category  RocketWeb
 * @package   RocketWeb_GoogleBaseFeedGenerator
 * @copyright Copyright (c) 2015 RocketWeb (http://rocketweb.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author    RocketWeb
 */
class RocketWeb_GoogleBaseFeedGenerator_Model_Observer
{
    /**
     * Process the schedule data and adds messages to queue
     */
    public function processSchedule()
    {
        // Get correct magento hour
        $time = new Zend_Date(Mage::getModel('core/date')->timestamp(time()), Zend_Date::TIMESTAMP);
        $hour = $time->get(Zend_Date::HOUR_SHORT);

        /** @var RocketWeb_GoogleBaseFeedGenerator_Model_Mysql4_Feed_Schedule_Collection $collection */
        $collection = Mage::getResourceModel('googlebasefeedgenerator/feed_schedule_collection');
        $collection->getSelect()->where('TO_DAYS(`processed_at`) < TO_DAYS(\''. $time->get(Zend_Date::ISO_8601). '\')
                                         AND start_at = '. $hour);

        foreach ($collection as $item) {
            $feed = Mage::getModel('googlebasefeedgenerator/feed')->load($item->getFeedId());
            if ($feed->getId() && $feed->isAllowed()) {
                Mage::getModel('googlebasefeedgenerator/queue')->send($feed, 'schedule', $item);
                $item->setData('processed_at', $time->get(Zend_Date::ISO_8601))
                     ->save();
                $feed->saveStatus(RocketWeb_GoogleBaseFeedGenerator_Model_Feed_Status::STATUS_PENDING);
            }
        }
    }

    /**
     * Processes the queue and runs the generator
     *
     * @param Mage_Cron_Model_Schedule $schedule
     */
    public function processQueue($schedule)
    {
        /** @var RocketWeb_GoogleBaseFeedGenerator_Model_Queue $queue */
        $queue = Mage::getModel('googlebasefeedgenerator/queue')->read();
        if ($schedule->getVerbose()) {
            echo $queue->getId() ? 
                'Processing queue ID: '. $queue->getId(). PHP_EOL : 'Nothing in the queue to process'. PHP_EOL;
        }
        if (!$queue->getId()) {
            return;
        }
        // lock the queue so that another cron does not process it
        $queue->lock();

        /** @var RocketWeb_GoogleBaseFeedGenerator_Model_Feed $feed */
        $feed = Mage::getModel('googlebasefeedgenerator/feed')->load($queue->getFeedId());
        $messages = $feed->getMessages();
        if ($messages['progress'] == '100') {
            $feed->setMessages(array('date' => date("Y-m-d H:i:s"), 'progress' => 0, 'added' => 0, 'skipped' => 0));
        }
        $feed->saveStatus(RocketWeb_GoogleBaseFeedGenerator_Model_Feed_Status::STATUS_PROCESSING);

        $feedSchedule = Mage::getModel('googlebasefeedgenerator/feed_schedule')->load($queue->getScheduleId());
        if (!$feedSchedule->getId()) {
            $feedSchedule = $feed->getSchedule();
        }
        $feed->setSchedule($feedSchedule);

        $generator = Mage::helper('googlebasefeedgenerator')->getGenerator($feed)
            ->addData(array(
                'schedule_id'   => $schedule->getScheduleId(),
                'verbose'       => $schedule->getVerbose())
            );

        try {
            $generator->run();
        }
        catch (RocketWeb_GoogleBaseFeedGenerator_Model_Exception $e) {
            // Ending batch earlier due memory limit. Do not release the queue.
            $generator->log($e->getMessage());
        }
        catch (Exception $e) {
            try {
                $queue->delete();
                $feed->saveStatus(RocketWeb_GoogleBaseFeedGenerator_Model_Feed_Status::STATUS_ERROR);
                $generator->log($e->getMessage());
            } catch (Exception $l){
                Mage::log($e->getMessage(), Zend_Log::ERR, $feed->getLogFile(), true);
            }
        }
        
        $batchMode = $feedSchedule->getBatchMode();

        // Unlock the queue message so that it ca process next batch
        if ($batchMode && !$generator->getBatch()->completedForToday()) {
            $queue->unlock();
        }

        // Set the feed as completed
        if (!$batchMode || ($batchMode && $generator->getBatch()->completedForToday())) {
            $queue->delete();
            $feed->saveStatus(RocketWeb_GoogleBaseFeedGenerator_Model_Feed_Status::STATUS_COMPLETED);
            $errors = $this->_doFtpUpload($feed, $generator->getFeedPath());
            if ($errors === true) {
                $generator->log('Feed was successfully uploaded to all the FTP accounts');
            } else {
                foreach ($errors as $error) {
                    $generator->log($error);
                }
            }
        }
    }

    /**
     * Do feed ftp upload if configured so
     * 
     * @param RocketWeb_GoogleBaseFeedGenerator_Model_Feed $feed
     * @param string $feedPath
     * @return boolean | array
     */
    protected function _doFtpUpload($feed, $feedPath) 
    {
        $errors = array();
        $ftpAccounts  = Mage::getResourceModel('googlebasefeedgenerator/feed_ftp_collection')
            ->addFeedFilter($feed->getId())
            ->load();
        foreach ($ftpAccounts as $account) {
            $result = Mage::helper('googlebasefeedgenerator')->ftpUpload($account, true, $feedPath);
            if ($result !== true) {
                $errors[] = $result;
            }
        }
        return (count($errors) == 0 && count($ftpAccounts) > 0) ? true : $errors;
    }
}
