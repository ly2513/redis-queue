<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/23
 * Time: 下午3:41
 * Email: liyong@addnewer.com
 */
namespace TradingMax\Console\Queue;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use RedisQueue\Resque;

//use TradingMax\Extend\EloquentAndQueue as initQueue;


class ListFailedCommand extends Command
{
    /**
     * 命令配置
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('queue:failed')
            ->setDescription('List all of the failed queue jobs.')
            ->setDefinition([
                new InputOption(
                    'job-id', null, InputOption::VALUE_NONE,
                    'A queue job ID.'
                ),
                new InputOption(
                    'queue-name', null, InputOption::VALUE_NONE,
                    'queue name.'
                ),
            ]);
    }

    /**
     * 命令操作
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $jobId = $input->getOption('job-id');
        $jobId = $jobId ? $jobId : '';
        
        $jobName = $input->getOption('queue-name');
        $jobName = $jobName ? $jobName : '';
        $this->initConf();

        $redisServer = $_SERVER['REDIS_BACKEND'];
        $redisServer = $redisServer ? $redisServer : '127.0.0.1:6379';
        Resque::setBackend($redisServer);
        $length = Resque::redis()->llen('failed');
        $failQueueList = Resque::redis()->lrange('failed', 0, $length - 1);
        foreach ($failQueueList as $keys => $values) {
            $failQueueList[$keys] = json_decode($values, true);
        }

//        if ($jobId) {
//            foreach ($failQueueList as $keys => $values) {
//                    if ($values['payload']['id'] === $jobId) {
//                        echo $values['payload']['id'] . PHP_EOL;
//                    }
//            }
//        }
//
//        if ($jobName) {
//            foreach ($failQueueList as $key => $value) {
//
//            }
//        }

        foreach ($failQueueList as $key => $value) {
            $date = date('Y-m-d H:i:s', strtotime($value['failed_at']));
            $id = $value['payload']['id'];
            $class = $value['payload']['class'];
            $queue = $value['queue'];
            $error = $value['error'];
            $string = 'QueueID: <info>%s</info>  ' . 'Queue: <info>%s</info>  ' . 'Class: <info>%s</info>  ' .
                'Date: <info>%s</info>  ' . 'Error: <info>%s</info>';
            $output->writeln(sprintf($string, $id, $queue, $class, $date, $error));
        }
    }

    private function initConf()
    {
        require APPLICATION_ROOT . 'application/config/queue.php';
        $_SERVER['QUEUE'] = $config['queue']['queue'];
        $_SERVER['COUNT'] = $config['queue']['count'];
        $_SERVER['REDIS_BACKEND'] = $config['queue']['host'] . ':' . $config['queue']['port'];
        $_SERVER['LOGGING'] = $config['queue']['logging'];
        $_SERVER['VERBOSE'] = $config['queue']['verbose'];
        $_SERVER['VVERBOSE'] = $config['queue']['vverbose'];
        $_SERVER['INTERVAL'] = $config['queue']['sleep'];
        $_SERVER['PIDFILE'] = $config['queue']['pidfile'];
    }

}
