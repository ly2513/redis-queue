<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 11:29
 * Email: liyong@addnewer.com
 */
$config['queue']['host'] = '127.0.0.1';
$config['queue']['port'] = '6379';
// 进程睡眠时间
$config['queue']['sleep'] = 1;
// 指定当前的Worker只负责处理default队列 ,如果设置 * ,就是处理所有队列,也可以使用',',如'list1,list2,list3'。
$config['queue']['queue']   = 'default';
$config['queue']['logging'] = 1;
$config['queue']['verbose'] = 1;
//比较详细的 log， VVERBOSE=1 debug 的时候可以打开来看
$config['queue']['vverbose'] = 1;
// 设定 worker 数量
$config['queue']['count'] = 1;
// 设置任务目录
$config['queue']['jobPath'] = APPLICATION_ROOT . '/Job/';
// 设置日志目录
$config['queue']['logPath'] = APPLICATION_ROOT . '/Log/';
// 设置如果失败将执行的次数
$config['queue']['executionTimes'] = 3;
// 如果你是简单 worker,可以指定 PIDFILE 把pid写入
$config['queue']['pidfile'] = '';
//$config['queue']['pidfile'] = '/var/run/resQueue.pid ';
// 研发组邮箱,用英文半角分号隔开
$config['queue']['emailGroup'] = '626375290@qq.com';
