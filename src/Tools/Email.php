<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 16:01
 * Email: liyong@addnewer.com
 */
namespace Tools;
use Con\Email as ConfigEmail ;
/**
 * Class Email
 *
 * @package Tools
 */
class Email
{
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
            return $this->email->send() ? true : false;
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
        $this->email = new \PHPmailer();
        $this->email->isSMTP();
        $this->email->SMTPAuth   = true;
        $this->email->SMTPSecure = ConfigEmail::$connectType;
        $this->email->Host       = ConfigEmail::$host;
        $this->email->Username   = ConfigEmail::$username;
        $this->email->Password   = ConfigEmail::$password;
        $this->email->Port       = ConfigEmail::$port;
        $this->email->CharSet    = ConfigEmail::$charset;
        $this->email->isHTML     = ConfigEmail::$isHTML;
        $this->email->AltBody    = ConfigEmail::$altBody;
        //回复邮件设置
        $this->email->From      = ConfigEmail::$username;
        $this->email->FromName  = ConfigEmail::$name;
        $this->email->errorInfo = null;
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
        require_once VENDOR_PATH . 'phpmailer/phpmailer/PHPMailerAutoload.php';
    }
}