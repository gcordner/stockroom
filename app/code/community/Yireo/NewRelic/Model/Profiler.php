<?php
/**
 * NewRelic plugin for Magento
 *
 * @package     Yireo_NewRelic
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Simplified BSD License
 */

/**
 * Class Yireo_NewRelic_Model_Profiler
 */
class Yireo_NewRelic_Model_Profiler
{
    /**
     * Timers for code profiling
     *
     * @var array
     */
    static protected $_timers = array();

    /**
     * @var bool
     */
    static protected $_enabled = false;

    /**
     * @var bool
     */
    static protected $_memory_get_usage = false;

    /**
     * Method to initialize the profiler
     *
     */
    static public function init()
    {
        // Do not continue when the PHP-extension "newrelic" is not found
        if (!extension_loaded('newrelic')) {
            return;
        }

        // Do not continue when the proper functions are not loaded
        if (!function_exists('newrelic_add_custom_tracer')) {
            return;
        }

        // Add generic NewRelic calls that don't have dependancies on Magento
        static $initialized = false;
        if ($initialized == false) {
            newrelic_add_custom_tracer('Mage::getModel');
            newrelic_add_custom_tracer('Mage::getSingleton');
            newrelic_add_custom_tracer('Mage::helper');
            newrelic_add_custom_tracer('Mage::log');
            newrelic_add_custom_tracer('Mage_Core_Model_App::_initCache');
            newrelic_add_custom_tracer('Mage_Core_Model_Config::loadDb');
            newrelic_add_custom_tracer('Mage_Core_Model_Config::loadModules');
            newrelic_add_custom_tracer('include');
            newrelic_add_custom_tracer('include_once');
            newrelic_add_custom_tracer('require');
            newrelic_add_custom_tracer('require_once');
            $initialized = true;
        }

        // Register the Magento request (once it is loaded in Magento) with NewRelic
        static $request_logged = false;
        if ($request_logged == false) {
            $request = Mage::app()->getRequest();
            if (!empty($request)) {
                $request_logged = true;
                if (function_exists('newrelic_name_transaction')) {
                    newrelic_name_transaction(Mage::helper('newrelic')->getSystemPath());
                }
            }
        }
    }

    /**
     * Below is a copy of the original Varien_Profiler class
     * with one exception: the init() method is called from within resume() and pause()
     */
    static public function enable()
    {
        self::$_enabled = true;
        self::$_memory_get_usage = function_exists('memory_get_usage');
    }

    /**
     *
     */
    static public function disable()
    {
        self::$_enabled = false;
    }

    /**
     * @param $timerName
     */
    static public function reset($timerName)
    {
        self::$_timers[$timerName] = array(
            'start' => false,
            'count' => 0,
            'sum' => 0,
            'realmem' => 0,
            'emalloc' => 0,
        );
    }

    /**
     * @param $timerName
     */
    static public function resume($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        self::init();

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (self::$_memory_get_usage) {
            self::$_timers[$timerName]['realmem_start'] = memory_get_usage(true);
            self::$_timers[$timerName]['emalloc_start'] = memory_get_usage();
        }
        self::$_timers[$timerName]['start'] = microtime(true);
        self::$_timers[$timerName]['count']++;
    }

    /**
     * @param $timerName
     */
    static public function start($timerName)
    {
        self::resume($timerName);
    }

    /**
     * @param $timerName
     */
    static public function pause($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        self::init();

        $time = microtime(true); // Get current time as quick as possible to make more accurate calculations

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (false !== self::$_timers[$timerName]['start']) {
            self::$_timers[$timerName]['sum'] += $time - self::$_timers[$timerName]['start'];
            self::$_timers[$timerName]['start'] = false;
            if (self::$_memory_get_usage) {
                self::$_timers[$timerName]['realmem'] += memory_get_usage(true) - self::$_timers[$timerName]['realmem_start'];
                self::$_timers[$timerName]['emalloc'] += memory_get_usage() - self::$_timers[$timerName]['emalloc_start'];
            }
        }
    }

    /**
     * @param $timerName
     */
    static public function stop($timerName)
    {
        self::pause($timerName);
    }

    /**
     * @param $timerName
     * @param string $key
     *
     * @return bool|mixed
     */
    static public function fetch($timerName, $key = 'sum')
    {
        if (empty(self::$_timers[$timerName])) {
            return false;
        } elseif (empty($key)) {
            return self::$_timers[$timerName];
        }
        switch ($key) {
            case 'sum':
                $sum = self::$_timers[$timerName]['sum'];
                if (self::$_timers[$timerName]['start'] !== false) {
                    $sum += microtime(true) - self::$_timers[$timerName]['start'];
                }
                return $sum;

            case 'count':
                $count = self::$_timers[$timerName]['count'];
                return $count;

            case 'realmem':
                if (!isset(self::$_timers[$timerName]['realmem'])) {
                    self::$_timers[$timerName]['realmem'] = -1;
                }
                return self::$_timers[$timerName]['realmem'];

            case 'emalloc':
                if (!isset(self::$_timers[$timerName]['emalloc'])) {
                    self::$_timers[$timerName]['emalloc'] = -1;
                }
                return self::$_timers[$timerName]['emalloc'];

            default:
                if (!empty(self::$_timers[$timerName][$key])) {
                    return self::$_timers[$timerName][$key];
                }
        }
        return false;
    }

    /**
     * @return array
     */
    static public function getTimers()
    {
        return self::$_timers;
    }

    /**
     * Output SQl Zend_Db_Profiler
     *
     */
    static public function getSqlProfiler($res)
    {
        if (!$res) {
            return '';
        }
        $out = '';
        $profiler = $res->getProfiler();
        if ($profiler->getEnabled()) {
            $totalTime = $profiler->getTotalElapsedSecs();
            $queryCount = $profiler->getTotalNumQueries();
            $longestTime = 0;
            $longestQuery = null;

            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }

            $out .= 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "<br>";
            $out .= 'Average query length: ' . $totalTime / $queryCount . ' seconds' . "<br>";
            $out .= 'Queries per second: ' . $queryCount / $totalTime . "<br>";
            $out .= 'Longest query length: ' . $longestTime . "<br>";
            $out .= 'Longest query: <br>' . $longestQuery . "<hr>";
        }
        return $out;
    }
}
