<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongLi
 * Date: 16/9/28
 * Time: 下午12:43
 * Email: liyong@addnewer.com
 */
namespace Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RedisQueue\ResQueue;
use RedisQueue\ReQueue\Worker;

class WorkQueueCommand extends QueueCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 命令配置
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('queue:work')->setDescription('work a queue.')->setDefinition([
                new InputOption('queue-name', null, InputOption::VALUE_NONE, 'queue name.'),
                new InputOption('redis-host', 'rh', InputOption::VALUE_NONE, 'Redis service host.'),
                new InputOption('redis-port', 'rp', InputOption::VALUE_NONE, 'Redis service port.'),
            ]);
    }

    /**
     * 命令操作
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host        = $input->getOption('redis-host');
        $port        = $input->getOption('redis-port');
        $host        = $host ? $host : '127.0.0.1';
        $port        = $port ? $port : 6379;
        $redisServer = $host . ':' . $port;
        //        $this->initConf();
        $redisBackEnd = $_SERVER['REDIS_BACKEND'];
        $redisBackEnd ? ResQueue::setBackend($redisBackEnd) : ResQueue::setBackend($redisServer);
        $queueName = $input->getOption('queue-name');
        $queue = $queueName ? $queueName : $_SERVER['QUEUE'];
        if (empty($queue)) {
            die("Set QUEUE env var containing the list of queues to work.\n");
        }
        $logLevel = 0;
        $logging  = $_SERVER['LOGGING'];
        $verbose  = $_SERVER['VERBOSE'];
        $vverbose = $_SERVER['VVERBOSE'];
        // 设置调试log的相关信息
        if (!empty($logging) || !empty($verbose)) {
            $logLevel = Worker::LOG_NORMAL;
        } else if (!empty($vverbose)) {
            $logLevel = Worker::LOG_VERBOSE;
        }
        // 加载所有的job类
        $appInclude = getenv('APP_INCLUDE');
        if ($appInclude) {
            if (!file_exists($appInclude)) {
                die('APP_INCLUDE (' . $appInclude . ") does not exist.\n");
            }
            require_once $appInclude;
        }
        // 隔多久执行 时间:秒级
        $interval = $_SERVER['INTERVAL'] ? $_SERVER['INTERVAL'] : 5;
        // 设置woker数量
        $count = $_SERVER['COUNT'] ? $_SERVER['INTERVAL'] : 1;
        if ($count > 1) {
            for ($i = 0; $i < $count; ++$i) {
                $pid = pcntl_fork();
                if ($pid == -1) {
                    die("Could not fork worker " . $i . "\n");
                } // 开始worker的子进程
                else if (!$pid) {
                    $queues           = explode(',', $queue);
                    $worker           = new Worker($queues);
                    $worker->logLevel = $logLevel;
                    fwrite(STDOUT, '*** Starting worker ' . $worker . "\n");
                    $worker->work($interval);
                    break;
                }
            }
        } else { // 开启一个简单的worker进程
            $queues           = explode(',', $queue);
            $worker           = new Worker($queues);
            $worker->logLevel = $logLevel;
            $pidFile = $_SERVER['PIDFILE'];
            if ($pidFile) {
                file_put_contents($pidFile, getmypid()) or die('Could not write PID information to ' . $pidFile);
            }
            $output->writeln(sprintf('*** Starting worker "<info>%s</info>"', $worker));
            $worker->work($interval);
        }
    }

}

