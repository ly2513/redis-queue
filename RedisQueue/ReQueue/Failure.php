<?php
/**
 * Redisent, a Redis interface for the modest
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/30
 * Time: 下午1:04
 * Email: liyong@addnewer.com
 */

namespace RedisQueue\ReQueue;

use RedisQueue\ReQueue\Failure\FailureInterface;
use RedisQueue\ReQueue\Worker;
use RedisQueue\ReQueue\Failure\FailureRedis;
use Exception;


/**
 * Failed ResQueue job.
 *
 * @package RedisQueue\ReQueue
 */
class Failure
{
    /**
     * @var string Class name representing the backend to pass failed jobs off to.
     */
    private static $backend;

    /**
     * Create a new failed job on the backend.
     *
     * @param                            $payload   The contents of the job that has just failed.
     * @param Exception                  $exception The exception generated when the job failed to run.
     * @param \RedisQueue\ReQueue\Worker $worker Instance of redisQueue_Worker that was running this job when it failed.
     * @param                            $queue The name of the queue that this job was fetched from.
     */
    public static function create($payload, Exception $exception, Worker $worker, $queue)
    {
        $backend = self::getBackend();
        new $backend($payload, $exception, $worker, $queue);
    }

    /**
     * Return an instance of the backend for saving job failures.
     *
     * @return object Instance of backend object.
     */
    public static function getBackend()
    {
        if (self::$backend === null) {
            self::$backend = 'FailureRedis';
        }

        return self::$backend;
    }

    /**
     * Set the backend to use for raised job failures. The supplied backend
     * should be the name of a class to be instantiated when a job fails.
     * It is your responsibility to have the backend class loaded (or autoloaded)
     *
     * @param string $backend The class name of the backend to pipe failures to.
     */
    public static function setBackend($backend)
    {
        self::$backend = $backend;
    }
}