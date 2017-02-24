<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/29
 * Time: 下午1:56
 * Email: liyong@addnewer.com
 */
if (!defined('APPLICATION_ROOT')) {
    die('Access Denied');
}
function do_queue_load($class)
{
    if ($class) {
        $file = str_replace('\\', '/', $class);
        // 加载队列
        $queueFiles = APPLICATION_ROOT . $file . '.php';
        if (file_exists($queueFiles)) {
            require $queueFiles;
        }
    }
}

spl_autoload_register('do_queue_load');

