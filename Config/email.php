<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 15:58
 * Email: liyong@addnewer.com
 */
// 邮箱服务器
$config['mail']['host'] = 'smtp.exmail.qq.com';
// 邮箱服务器端口
$config['mail']['port'] = '465';
// 账户(需配置)
$config['mail']['username'] = '';
// 密码(需配置)
$config['mail']['password'] = '';
// 账户名称,主要显示
$config['mail']['name'] = 'ResQueue';
// 加密方式
$config['mail']['smtpSecure'] = 'ssl';
// 编码
$config['mail']['charset'] = 'UTF-8';
// 是否支持Html
$config['mail']['isHTML'] = true;
$config['mail']['AltBody'] = 'This is the body in plain text for non-HTML mail clients';
$config['mail']['connectType'] = 'ssl';
if (!$config['mail']['host'] || !$config['mail']['username'] || !$config['mail']['password']) {
    throw new Exception('The Email Is Not Config');
}
?>
