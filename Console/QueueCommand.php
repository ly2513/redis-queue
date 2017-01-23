<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/28
 * Time: 下午1:04
 * Email: liyong@addnewer.com
 */
namespace Console;

use Symfony\Component\Console\Command\Command ;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class QueueCommand extends Command
{

    /**
     * A config array of database
     * @var null
     */
    public $db =   NULL;

    /**
     * Create a instance of capsule
     * @var null
     */
    public $capsule =   NULL;

    /**
     * Create a instance of Eloquent
     * @var null
     */
//    public $eloquent = NULL;

    public function __construct()
    {
        parent::__construct();

        // init Queue
        $this->initQueueConf();


        // init Eloquent
        $this->initEloquent();

        // init Database
        $this->initDB();

    }

    /**
     * 加载 Eloquent
     */
    private function initEloquent()
    {
        require_once APPLICATION_ROOT . 'vendor/autoload.php';
        
        #初始化 Capsule Manager
        $this->capsule = new Capsule;
    }

    /**
     * 初始化DB配置
     */
    private function initDB()
    {
        require APPLICATION_ROOT .'application/config/database.php';
        if(!isset($db)) {
            throw new \Exception('Can Load Db Config For Eloquent');
        }

        if(!isset($db[$active_group])) {
            throw new \Exception('Can Init Active Group Config Db For Eloquent');
        }

        $this->db =   $db;

        //$this->initDefaultDb($db[$active_group], $active_group);

        $db['default'] = $db[$active_group];

        foreach($db as $name=>$dbConfig) {
            $dbConfig = $this->transCiToEloquent($dbConfig);
            $this->capsule->addConnection($dbConfig, $name);
        }

        $this->capsule->bootEloquent();
        
    }

    /**
     * 用来转化CI的 DB配置=>Eloquent
     *
     * DB driver Eloquent 支持 mysql/pgsql/sqlite/sqlsrv
     */
    private function transCiToEloquent($config)
    {
        $db['driver'] =   'mysql';
        $db['host']      =   $config['hostname'];
        $db['database']  =   $config['database'];
        $db['username']  =   $config['username'];
        $db['password']  =   $config['password'];
        $db['charset']   =   $config['char_set'];
        $db['collation'] =   $config['dbcollat'];
        $db['prefix']    =   $config['dbprefix'];
        return $db;
    }

    public function getCapsule() {
        return $this->capsule;
    }

    private function initQueueConf()
    {
        date_default_timezone_set('PRC');
        // 队列配置
        require APPLICATION_ROOT . 'application/config/queue.php';
        $_SERVER['QUEUE'] = $config['queue']['queue'];
        $_SERVER['COUNT'] = $config['queue']['count'];
        $_SERVER['REDIS_BACKEND'] = $config['queue']['host'] . ':' . $config['queue']['port'];
        $_SERVER['LOGGING'] = $config['queue']['logging'];
        $_SERVER['VERBOSE'] = $config['queue']['verbose'];
        $_SERVER['VVERBOSE'] = $config['queue']['vverbose'];
        $_SERVER['INTERVAL'] = $config['queue']['sleep'];
        $_SERVER['PIDFILE'] = $config['queue']['pidfile'];
        $_SERVER['TIMES'] = $config['queue']['executionTimes'];

    }

    
    
    
}