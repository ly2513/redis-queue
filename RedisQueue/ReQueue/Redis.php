<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongLi
 * Date: 16/10/01
 * Time: 11:28
 * Email: liyong@addnewer.com
 */

namespace RedisQueue\ReQueue;

use RedisQueue\RedisSent\Redisent;
use RedisException;

/**
 * Extended Redisent class used by ResQueue for all communication with
 * redis. Essentially adds namespace support to Redisent.
 *
 * @package		ResQueue/Redis
 * @author		Chris Boulton <chris@bigcommerce.com>
 * @license		http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Extended Redisent class used by ResQueue for all communication with
 * redis. Essentially adds namespace support to Redisent.
 *
 * Class Redis
 * @package RedisQueue\ReQueue
 * @author yongli  <liyong@addnewer.com>
 */
class Redis extends Redisent
{
	
    /**
     * Redis namespace
     * @var string
     */
    private static $defaultNamespace = 'resQueue:';

	/**
	 * @var array List of all commands in Redis that supply a key as their
	 *	first argument. Used to prefix keys with the resQueue namespace.
	 */
	private $keyCommands = array(
		'exists',
		'del',
		'type',
		'keys',
		'expire',
		'ttl',
		'move',
		'set',
		'get',
		'getset',
		'setnx',
		'incr',
		'incrby',
		'decr',
		'decrby',
		'rpush',
		'lpush',
		'llen',
		'lrange',
		'ltrim',
		'lindex',
		'lset',
		'lrem',
		'lpop',
		'rpop',
		'sadd',
		'srem',
		'spop',
		'scard',
		'sismember',
		'smembers',
		'srandmember',
		'zadd',
		'zrem',
		'zrange',
		'zrevrange',
		'zrangebyscore',
		'zcard',
		'zscore',
		'zremrangebyscore',
		'sort'
	);
	// sinterstore
	// sunion
	// sunionstore
	// sdiff
	// sdiffstore
	// sinter
	// smove
	// rename
	// rpoplpush
	// mget
	// msetnx
	// mset
	// renamenx
	
	/**
	 * Set Redis namespace (prefix) default: resQueue
	 * @param string $namespace
	 */
	public static function prefix($namespace)
	{
	    if (strpos($namespace, ':') === false) {
	        $namespace .= ':';
	    }
	    self::$defaultNamespace = $namespace;
	}
	
	/**
	 * Magic method to handle all function requests and prefix key based
	 * operations with the {self::$defaultNamespace} key prefix.
	 *
	 * @param string $name The name of the method called.
	 * @param array $args Array of supplied arguments to the method.
	 * @return mixed Return value from Resident::call() based on the command.
	 */
	public function __call($name, $args) {
		$args = func_get_args();
		if(in_array($name, $this->keyCommands)) {
		    $args[1][0] = self::$defaultNamespace . $args[1][0];
		}
		try {
			return parent::__call($name, $args[1]);
		}
		catch(RedisException $e) {
			return false;
		}
	}
}
?>