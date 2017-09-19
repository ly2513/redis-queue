<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/28
 * Time: 11:29
 * Email: liyong@addnewer.com
 */
namespace Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RedisQueue\ResQueue;
use RedisQueue\ReQueue\Worker;

class ListenCommand extends QueueCommand
{

    /**
     * 命令配置
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('queue:listen')->setDescription('Listen to a given queue.')->setDefinition([
            new InputOption('job-id', null, InputOption::VALUE_NONE, 'A queue job ID.'),
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
        Worker::pruneDeadWorkers();
        $pid = getmypid();
        echo $pid;
        $redisServer = $_SERVER['REDIS_BACKEND'];
        $redisServer = $redisServer ? $redisServer : '127.0.0.1:6379';
        Resque::setBackend($redisServer);
        $length        = Resque::redis()->llen('failed');
        $failQueueList = Resque::redis()->lrange('failed', 0, $length - 1);
        foreach ($failQueueList as $keys => $values) {
            $failQueueList[$keys] = json_decode($values, true);
        }
        if ($jobId) {
            foreach ($failQueueList as $keys => $values) {
                if ($values['payload']['id'] === $jobId) {
                    echo $values['payload']['id'] . PHP_EOL;
                }
            }
        }
        foreach ($failQueueList as $key => $value) {
            $date   = date('Y-m-d H:i:s', strtotime($value['failed_at']));
            $id     = $value['payload']['id'];
            $class  = $value['payload']['class'];
            $queue  = $value['queue'];
            $error  = $value['error'];
            $string = 'QueueID: <info>%s</info>  ' . 'Queue: <info>%s</info>  ' . 'Class: <info>%s</info>  ' . 'Date: <info>%s</info>  ' . 'Error: <info>%s</info>';
            $output->writeln(sprintf($string, $id, $queue, $class, $date, $error));
        }
    }

}
