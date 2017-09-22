<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 17/1/23
 * Time: 11:29
 * Email: liyong@addnewer.com
 */
namespace Con;

class Queue
{
    /**
     * redis的主机
     *
     * @var string
     */
    public static $host = '127.0.0.1';

    /**
     * @var string
     */
    public static $port = '6379';

    /**
     * 进程睡眠时间
     *
     * @var int
     */
    public static $sleep = 1;

    /**
     * 指定当前的Worker只负责处理default队列 ,如果设置 * ,就是处理所有队列,也可以使用',',如'list1,list2,list3'。
     *
     * @var string
     */
    public static $queue = 'default';

    /**
     * @var int
     */
    public static $logging = 1;

    /**
     * @var int
     */
    public static $verbose = 1;

    /**
     * 比较详细的Log， VVERBOSE=1 debug 的时候可以打开来看
     *
     * @var int
     */
    public static $vVerbose = 1;

    /**
     * 设定Worker数量
     *
     * @var int
     */
    public static $count = 1;

    /**
     * 设置任务目录
     *
     * @var string
     */
    public static $jobPath = LIB_PATH . '/Job/';

    /**
     * 设置日志目录
     *
     * @var string
     */
    public static $logPath = LIB_PATH . '/Log/';

    /**
     * 设置如果失败将执行的次数
     *
     * @var int
     */
    public static $executionTimes = 3;

    /**
     * 如果你是简单 worker,可以指定 PIDFILE 把pid写入
     *
     * @var string
     */
    public static $pidfile = '';
//    public $pidfile = '/var/run/resQueue.pid ';

    /**
     * 研发组邮箱,用英文半角分号隔开
     *
     * @var string
     */
    public static $emailGroup = '626375290@qq.com';
}
