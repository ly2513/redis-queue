<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/10/02
 * Time: 10:28
 * Email: liyong@addnewer.com
 */
namespace RedisQueue\ReQueue\Job;

use Exception;


/**
 * Class DontPerform Exception to be thrown if a job should not be performed/run.
 *
 * @package RedisQueue\ReQueue\Job
 * @author  yongli  <liyong@addnewer.com>
 */
class DontPerform extends Exception
{

}