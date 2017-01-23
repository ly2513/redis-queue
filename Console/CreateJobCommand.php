<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/28
 * Time: 下午12:43
 * Email: liyong@addnewer.com
 */
namespace Console;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Console\QueueCommand;
use RedisQueue\ResQueue;

use TradingMax\Model\Model;
use TradingMax\Model\JobModel;


//use RedisQueue\ReQueue\Worker;


class CreateJobCommand extends QueueCommand
{
    protected $table = 'rm_jobs';

    public $eloquenet;

    public function __construct()
    {
        parent::__construct();

        $this->eloquenet = new Model();
    }

    /**
     * 命令配置
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('queue:create')
            ->setDescription('Create a queue job with the redis.')
            ->setDefinition([
                new InputOption(
                    'job-name', 'j', InputOption::VALUE_REQUIRED,
                    'Create a queue job name.'
                ),
                new InputOption(
                    'job-describe', 'd', InputOption::VALUE_NONE,
                    'Describe the function of the queue.'
                ),
                new InputOption(
//                    'queue-name', null, InputOption::VALUE_NONE | InputOption::VALUE_IS_ARRAY,
                    'queue-name', null, InputOption::VALUE_NONE,
                    'queue name.'
                ),
                new InputOption(
                    'redis-host', 'rh', InputOption::VALUE_NONE,
                    'Redis service host.'
                ),
                new InputOption(
                    'redis-port', 'rp', InputOption::VALUE_NONE,
                    'Redis service port.'
                ),
            ]);
    }

    /**
     * 命令操作
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // 获得工作任务
        $jobData = JobModel::select(['id', 'queue', 'payload'])->get()->toArray();

        $jobName = $input->getOption('job-name');

        $jobDir = APPLICATION_ROOT . 'application/third_party/TradingMax/Job/';

        is_dir($jobDir) or mkdir($jobDir, 0777, true);

        $host = $input->getOption('redis-host');
        $port = $input->getOption('redis-port');
        $host = $host ? $host : '127.0.0.1';
        $port = $port ? $port : 6379;
        $redisServer = $host . ':' . $port;

        $redisBackEnd = $_SERVER['REDIS_BACKEND'];

        $redisBackEnd ? ResQueue::setBackend($redisBackEnd) : ResQueue::setBackend($redisServer);

        $queueName = $input->getOption('queue-name');
        $queueName = $queueName ? $queueName : 'default';

        if (!$jobName) {
            foreach ($jobData as $key => $value) {
                $payload = json_decode($value['payload'], true);
                $jobName = $payload['class'] ? $payload['class'] : 'default';
                $jobFile = $jobDir . ucfirst($jobName) . 'Job.php';
                is_file($jobFile) or touch($jobFile);
                
                $str = <<<EOT
<?php 
/**
 * 
 *
 */
 namespace TradingMax\Job;
 
 use TradingMax\Model\EmailModel;
 
class 
EOT;
                $str .= ucfirst($jobName . 'Job') . PHP_EOL;
                $str .= <<<EOT
{
    protected \$email;
    
    /**
     * 运行任务
     *
     */
    public function perform()
    {
        sleep(120);
        
        \$this->email = new EmailModel;

        \$status = \$this->email->send('测试队列发送邮件', ['liyong@addnewer.com'], 'TradingMax');
        if(!\$status) {
            echo false;
        }
    }
    
}
EOT;

                file_put_contents($jobFile, $str);

                $description = 'Describe the function of the queue';
                //
                $args = [
                    'time'  => time(),
                    'array' => [
                        'test' => $description,
                    ],
                ];
                $args = $payload['data'] ? $payload['data'] : $args;

                try {
                    // 队列ID
                    $jobId = ResQueue::enqueue($queueName, $jobName . 'Job', $args, true);
                    $output->writeln(sprintf('Create queue job success, the queue job id is "<info>%s</info>"', $jobId));
                    return true;
                } catch (InvalidArgumentException $e) {
                    $output->writeln(sprintf('Create queue job error, the error message is "<info>%s</info>"', $e->getMessage()));

                    return false;

                } catch (Resque_Exception $e) {
                    $output->writeln(sprintf('Create queue job error, the error message is "<info>%s</info>"', $e->getMessage()));

                    return false;
                }

            }
        }

        // 单独创建
        $jobName = 'default';
        $description = $input->getOption('job-describe');
        $this->createJob($jobDir,$jobName,$description,$queueName,$output);
    }

    /**
     * 创建任务
     * @param $jobDir
     * @param $jobName
     * @param $description
     * @param $queueName
     * @param $output
     */
    private function createJob($jobDir, $jobName, $description, $queueName,$output)
    {
        $jobName = $jobName ? $jobName : 'default';
        $jobFile = $jobDir . ucfirst($jobName) . 'Job.php';
        is_file($jobFile) or touch($jobFile);
        $str = <<<EOT
<?php 
/**
 * 
 *
 */
 namespace TradingMax\Job;
 
 use TradingMax\Model\EmailModel;
 
class 
EOT;
        $str .= ucfirst($jobName . 'Job') . PHP_EOL;
        $str .= <<<EOT
{
    protected \$email;
    
    /**
     * 运行任务
     *
     */
    public function perform()
    {
        sleep(120);
        
        \$this->email = new EmailModel;

        \$status = \$this->email->send('测试队列发送邮件', ['liyong@addnewer.com'], 'TradingMax');
        if(!\$status) {
            echo false;
        }
    }
    
}
EOT;

        file_put_contents($jobFile, $str);

        $description = $description ? $description : 'Describe the function of the queue';
        //
        $args = [
            'time'  => time(),
            'array' => [
                'test' => $description,
            ],
        ];

        try {
            // 队列ID
            $jobId = ResQueue::enqueue($queueName, $jobName . 'Job', $args, true);
            $output->writeln(sprintf('Create queue job success, the queue job id is "<info>%s</info>"', $jobId));
        } catch (InvalidArgumentException $e) {
            $output->writeln(sprintf('Create queue job error, the error message is "<info>%s</info>"', $e->getMessage()));

        } catch (Resque_Exception $e) {
            $output->writeln(sprintf('Create queue job error, the error message is "<info>%s</info>"', $e->getMessage()));
        }
    }


}

