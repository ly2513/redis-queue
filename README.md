#RedisQueue目录
   + [RedisQueue组件描述](#redisQueue1)
   + [ResQueue的目录结构](#resQueue2)
   + [ResQueue使用前期准备](#resQueue3)



## <span id="redisQueue1"> RedisQueue组件介绍 </span>
一个基于redis的队列项目，结合了symfony的console组件，采用命令创建队列、执行队列。
**redisQueue**是一个流程相对简单(相对那些重量级的MQ),具有以下几个特点

+ **易理解**
+ **使用简单**
+ **易维护**
+ **易扩展**
+ **容错机制完善**

总而言之,这个队列组件很灵活(有点自吹啊,但是真的很灵活,不骗你),为了方便大家使用,我将这个组件的使用步骤[写在这了](#)。

`redisQueue`队列库是基于redis队列实现的一套队列任务处理机制，同时提供了`Symfony`的`command`组件的队列命令.

## <span id="resQueue2"> ResQueue的目录结构 </span>

```
----redisQueue
    |---- Config  // 相关配置目录
    |       |---- email.php
    |       |____ queue.php
    |---- Console // 队列相关命令目录
    |       |---- CreateJobCommand.php
    |       |---- ListenCammand.php
    |       |---- ListFailedCammand.php
    |       |---- QueueCammand.php
    |       |____ WorkQueueCammand.php
    |---- Demo // demo目录
    |---- Job  // 任务目录
    |       |____ SendEmailJob.php
    |---- Log  // 队列日志目录
    |---- RedisQueue // 队列组件目录
    |       |---- Extras
    |       |---- RedisSent
    |       |---- ReQueue
    |       |____ ResQueue.php
    |---- Tools     // 第三方组件目录,
    |---- vendor    // 组件目录
    |---- artisan   // 执行命令文件
    |____README.md  // RedisQueue组件说明文件

```
## <span id="resQueue3"> 使用前期准备 </span>

### 你需搭建以下几个环境
  + 搭建PHP环境
  + 安装Redis,及Redis的PHP扩展
  + 安装composer包管理工具

### 使用`composer`安装以下几个组件
  + `symfony`的`console`命令组件
  + `phpmailer`组件

### 使用`symfony/console`命令组件运行队列
>以下是队列相关运行命令,首先进入到组件的根目录,使用artisan运行相关命令

+ 创建队列命令

```php
  php artisan queue:create

```
+ 查看失败队列命令

```
php artisan queue:failed
```

+ 执行队列命令

```
php artisan queue:work
```

##<span id="resQueue3"> ResQueue使用步骤 </span>
+ 首先要知道怎么使用该组件创建队列,使用如下命令即可创建
```php
    php artisan queue:create
```
执行上面的创建命令,`redisQueue`会帮你创建默认的队列任务,默认的任务类为`SentEmailJob`。每个任务类中都有一个`perform`的方法,
队列进程主要是执行任务类中的这个方法来完成相应的队列任务。
该命令有自己的一套默认参数,我们来瞧瞧这个命令的的相关参数吧。
```sh
# 这命令有这些参数
queue:create [-j|--job-name JOB-NAME] [-d|--job-describe] [--queue-name] [-rh|--redis-host] [-rp|--redis-port]

job-name : 简写-j,即queue:create --job-name(-j) 队列任务名称,默认值:SentEmail
job-describe : 简写-d,即queue:create --job-describe(-d) 队列任务描述,默认值:Describe the function of the queue
queue-name : 即queue:create --queue-name 队列名称,默认值:default
redis-host : 简写-rh,即queue:create --redis-host(-rh) 队列的redis主机配置,默认值:127.0.0.1
redis-port : 简写-rd,即queue:create --redis-port(-rp) 队列的redis的端口配置,默认值:3306


```

```php
    // 任务逻辑方法
    public function perform()
    {
        $status = $this->email->sendEmail('测试队列发送邮件', ['liyong@addnewer.com'], 'RedisQueue');

        if(!$status) {
            $this->log->writeLog('发送失败');
            echo false;
        }
    }
```
如有需要,可以自定义任务名称(即任务类名称)遵守驼峰命名。
 
