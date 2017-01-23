<?php 
/**
* 
*
*/
namespace Job;

use Tools\Email;

class SentEmailJob
{
protected $email;

/**
 * 运行任务
 *
 */
public function perform()
{
    sleep(120);
    
    $this->email = new EmailModel;

    $status = $this->email->send('测试队列发送邮件', ['liyong@addnewer.com'], 'TradingMax');
    if(!$status) {
        echo false;
    }
}

}