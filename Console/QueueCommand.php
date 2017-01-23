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
        require APPLICATION_ROOT . 'Config/queue.php';
        $_SERVER['QUEUE']         = $config['queue']['queue'];
        $_SERVER['COUNT']         = $config['queue']['count'];
        $_SERVER['REDIS_BACKEND'] = $config['queue']['host'] . ':' . $config['queue']['port'];
        $_SERVER['LOGGING']       = $config['queue']['logging'];
        $_SERVER['VERBOSE']       = $config['queue']['verbose'];
        $_SERVER['VVERBOSE']      = $config['queue']['vverbose'];
        $_SERVER['INTERVAL']      = $config['queue']['sleep'];
        $_SERVER['PIDFILE']       = $config['queue']['pidfile'];
        $_SERVER['TIMES']         = $config['queue']['executionTimes'];
        $_SERVER['JOBPATH']       = $config['queue']['jobPath'];
        $_SERVER['LOGPATH']       = $config['queue']['logPath'];
        $_SERVER['emailGroup']    = $config['queue']['emailGroup'];
    }

}
