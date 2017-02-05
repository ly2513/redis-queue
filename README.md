# redisQueue
一个基于redis的队列项目，结合了symfony的console组件，采用命令创建队列、执行队列

`redisQueue`队列库是基于redis队列实现的一套队列任务处理机制，同时提供了`Symfony`的`command`组件的队列命令.

## ResQueue的目录结构

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
## 使用`composer`安装组件
  + `symfony`的`console`命令组件

  + `phpmailer`组件

## 使用`symfony/console`组件运行队列
>以下是队列相关运行命令,首先进入到组件的根目录,使用artisan运行相关命令

+ 创建队列命令

```
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
 
