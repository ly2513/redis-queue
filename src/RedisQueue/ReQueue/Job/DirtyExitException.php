<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/10/02
 * Time: 10:28
 * Email: liyong@addnewer.com
 */
namespace RedisQueue\ReQueue\Job;

use  RuntimeException;

/**
 * Class DirtyExitException for a job that does not exit cleanly.
 *
 * @package RedisQueue\ReQueue\Job
 * @author  yongli <liyong@addnewer.com>
 */
class DirtyExitException extends RuntimeException
{

}