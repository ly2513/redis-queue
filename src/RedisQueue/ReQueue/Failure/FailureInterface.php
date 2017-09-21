<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongLi
 * Date: 16/10/01
 * Time: 11:28
 * Email: liyong@addnewer.com
 */
namespace RedisQueue\ReQueue\Failure;

/**
 * Interface FailureInterface that all failure backends should implement.
 *
 * @package RedisQueue\ReQueue\Failure
 * @author  yongli <liyong@addnewer.com>
 */
interface FailureInterface
{
    /**
     * Initialize a failed job class and save it (where appropriate).
     *
     * @param object $payload   Object containing details of the failed job.
     * @param object $exception Instance of the exception that was thrown by the failed job.
     * @param object $worker    Instance of Resque_Worker that received the job.
     * @param string $queue     The name of the queue the job was fetched from.
     */
    public function __construct($payload, $exception, $worker, $queue);
}

?>