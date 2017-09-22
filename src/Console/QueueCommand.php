<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/28
 * Time: 下午1:04
 * Email: liyong@addnewer.com
 */
namespace Console;

use Symfony\Component\Console\Command\Command;
use Con\Queue;

class QueueCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
        // init Queue
        $this->initQueueConf();
    }

    /**
     * 队列配置
     */
    private function initQueueConf()
    {
        // 队列配置
        //        require APPLICATION_ROOT . 'Config/queue.php';
        $_SERVER['QUEUE']         = Queue::$queue;
        $_SERVER['COUNT']         = Queue::$count;
        $_SERVER['REDIS_BACKEND'] = Queue::$host . ':' . Queue::$port;
        $_SERVER['LOGGING']       = Queue::$logging;
        $_SERVER['VERBOSE']       = Queue::$verbose;
        $_SERVER['VVERBOSE']      = Queue::$vVerbose;
        $_SERVER['INTERVAL']      = Queue::$sleep;
        $_SERVER['PIDFILE']       = Queue::$pidfile;
        $_SERVER['TIMES']         = Queue::$executionTimes;
        $_SERVER['JOBPATH']       = Queue::$jobPath;
        $_SERVER['LOGPATH']       = Queue::$logPath;
        $_SERVER['emailGroup']    = Queue::$emailGroup;
    }

}
