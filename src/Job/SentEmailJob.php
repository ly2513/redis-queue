<?php
/**
 * 发送邮件任务
 *
 */
use Tools\Email;
use RedisQueue\ReQueue\Log;

class SentEmailJob
{
    private $email = null;

    private $log = null;

    public function __construct()
    {
        $this->email = new Email();
        $this->log   = new Log();
    }

    /**
     * 运行任务
     *
     */
    public function perform()
    {
        //        sleep(120);

        try{
            $status = $this->email->sendEmail('测试队列发送邮件', ['626375290@qq.com'], 'RedisQueue');
        }catch (Exception $e){
            $this->log->writeLog($e->getMessage());
        }
//        if (!$status) {
//            $this->log->writeLog('发送失败');
//            echo false;
//        }
    }
}
