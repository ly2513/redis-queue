<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/29
 * Time: 下午1:56
 * Email: liyong@addnewer.com
 */
if(!defined('APPLICATION_ROOT')) { die('Access Denied'); }

function do_queue_load($class)
{
    if($class) {
        $file = str_replace('\\', '/', $class);

        // 加载队列
        $queueFiles = APPLICATION_ROOT . 'application/third_party/Queue/'.$file . '.php';
        // 加载Eloquent数据库文件
        $eloquentFiles = APPLICATION_ROOT . 'vendor/'.$file . '.php';
//        $eloquentFiles = APPLICATION_ROOT . 'vendor/autoload.php';
        if(file_exists($queueFiles)) { require $queueFiles; }

        if(file_exists($eloquentFiles)) { require $eloquentFiles; }
//
        
//        require APPLICATION_ROOT.'system/core/CodeIgniter.php';


        
    }
}
spl_autoload_register('do_queue_load');

date_default_timezone_set('PRC');

