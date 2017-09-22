<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 15:58
 * Email: liyong@addnewer.com
 */
namespace Con;

class Email
{
    /**
     * 邮箱服务器
     *
     * @var string
     */
    public static $host = 'smtp.exmail.qq.com';

    /**
     * 邮箱服务器端口
     *
     * @var string
     */
    public static $port = '465';

    /**
     * 账户(需配置)
     *
     * @var string
     */
    public static $username = 'noreply@addnewer.com';

    /**
     * 密码(需配置)
     *
     * @var string
     */
    public static $password = 'nWaybb74rLYm';

    /**
     * 账户名称,主要显示
     *
     * @var string
     */
    public static $name = 'ResQueue';

    /**
     * 加密方式
     *
     * @var string
     */
    public static $smtpSecure = 'ssl';

    /**
     * 编码
     *
     * @var string
     */
    public static $charset = 'UTF-8';

    /**
     * 是否支持Html
     *
     * @var bool
     */
    public static $isHTML = true;

    /**
     * @var string
     */
    public static $altBody = 'This is the body in plain text for non-HTML mail clients';

    /**
     * 连接类型
     *
     * @var string
     */
    public static $connectType = 'ssl';

    public function __construct()
    {
        if (!self::$host || !self::$username || !self::$password) {
            throw new Exception('The Email Is Not Config');
        }
    }
}
