<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 16:01
 * Email: liyong@addnewer.com
 */

namespace Tools;

class Email {
    /**
     * 邮件对象
     *
     * @var
     */
    public $email;

    /**
     * 初始化邮件
     *
     * EmailModel constructor.
     */
    public function __construct()
    {
        $this->loadMailer();
        $this->initEmial();

    }

    /**
     * 发送邮件
     *
     * @param string $message
     * @param array  $user
     * @param string $title
     *
     * @return bool
     */
    public function sendEmail($message, array $user, $title = 'TradingMax')
    {
        //循环处理用户
        $this->email->clearAddresses();

        foreach ($user as $val) {
            $this->email->addAddress($val);
        }

        $this->email->Subject = $title;
        $this->email->Body    = $message;

        try {
            return $this->email->send() ? TRUE : FALSE;
        } catch (Exception $e) {
            $this->email->errorInfo = $e->getMessage();
            return $e->getMessage();
        }
    }

    /**
     * 初始化email配置
     */
    private function initEmial()
    {
        require APPLICATION_ROOT . 'Config/email.php';
        $this->email = new \PHPmailer();
        $this->email->isSMTP();
        $this->email->SMTPAuth   = TRUE;
        $this->email->SMTPSecure = $config['mail']['connectType'];
        $this->email->Host       = $config['mail']['host'];
        $this->email->Username   = $config['mail']['username'];
        $this->email->Password   = $config['mail']['password'];
        $this->email->Port       = $config['mail']['port'];
        $this->email->CharSet    = $config['mail']['charset'];
        $this->email->isHTML     = $config['mail']['isHTML'];
        $this->email->AltBody    = $config['mail']['AltBody'];
        //回复邮件设置
        $this->email->From      = $config['mail']['username'];
        $this->email->FromName  = $config['mail']['name'];
        $this->email->errorInfo = NULL;
    }

    /**
     * 添加抄送
     *
     * @param $emails
     */
    public function addCC($emails)
    {
        $emails = array_unique(array_filter(explode(';', $emails)));
        foreach ($emails as $email) {
            $this->email->addCC($email);
        }
    }

    /**
     * 添加附件
     *
     * @param $file
     */
    public function addAttachment($file)
    {
        $this->email->addAttachment($file);
    }

    /**
     * 加载 PHPMailer
     */
    private function loadMailer()
    {
        require_once APPLICATION_ROOT . 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
    }
}