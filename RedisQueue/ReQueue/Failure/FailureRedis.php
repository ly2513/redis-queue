<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongLi
 * Date: 16/10/01
 * Time: 11:28
 * Email: liyong@addnewer.com
 */

namespace RedisQueue\ReQueue\Failure;

use RedisQueue\ReQueue\Failure\FailureInterface;
use stdClass;
use RedisQueue\ResQueue;

/**
 * Class FailureRedis for storing failed ResQueue jobs.
 *
 * @package RedisQueue\ReQueue\Failure
 * @author yongli  <liyong@addnewer.com>
 */
class FailureRedis implements FailureInterface
{
	/**
	 * Initialize a failed job class and save it (where appropriate).
	 *
	 * @param object $payload Object containing details of the failed job.
	 * @param object $exception Instance of the exception that was thrown by the failed job.
	 * @param object $worker Instance of Resque_Worker that received the job.
	 * @param string $queue The name of the queue the job was fetched from.
	 */
	public function __construct($payload, $exception, $worker, $queue)
	{
		$data = new stdClass;
		$data->failed_at = date('Y-m-d H:i:s',time());
		$data->payload = $payload;
		$data->exception = get_class($exception);
		$data->error = $exception->getMessage();
		$data->backtrace = explode("\n", $exception->getTraceAsString());
		$data->worker = (string)$worker;
		$data->queue = $queue;
		$data = json_encode($data);
		ResQueue::redis()->rpush('failed', $data);
	}
}
?>