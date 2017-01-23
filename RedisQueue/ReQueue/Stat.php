<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/10/02
 * Time: 11:28
 * Email: liyong@addnewer.com
 */

namespace RedisQueue\ReQueue;

use RedisQueue\ResQueue;


/**
 * Class Stat redisQueue statistic management (jobs processed, failed, etc)
 * @package RedisQueue\ReQueue
 * @author  yongli <liyong@addnewer.com>
 */
class Stat
{
    /**
     * Get the value of the supplied statistic counter for the specified statistic.
     *
     * @param string $stat The name of the statistic to get the stats for.
     * @return mixed Value of the statistic.
     */
    public static function get($stat)
    {
        return (int)ResQueue::redis()->get('stat:' . $stat);
    }

    /**
     * Increment the value of the specified statistic by a certain amount (default is 1)
     *
     * @param string $stat The name of the statistic to increment.
     * @param int    $by The amount to increment the statistic by.
     * @return boolean True if successful, false if not.
     */
    public static function incr($stat, $by = 1)
    {
        return (bool)ResQueue::redis()->incrby('stat:' . $stat, $by);
    }

    /**
     * Decrement the value of the specified statistic by a certain amount (default is 1)
     *
     * @param string $stat The name of the statistic to decrement.
     * @param int    $by The amount to decrement the statistic by.
     * @return boolean True if successful, false if not.
     */
    public static function decr($stat, $by = 1)
    {
        return (bool)ResQueue::redis()->decrby('stat:' . $stat, $by);
    }

    /**
     * Delete a statistic with the given name.
     *
     * @param string $stat The name of the statistic to delete.
     * @return boolean True if successful, false if not.
     */
    public static function clear($stat)
    {
        return (bool)ResQueue::redis()->del('stat:' . $stat);
    }
}

?>