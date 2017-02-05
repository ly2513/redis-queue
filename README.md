#RedisQueue目录
   + [RedisQueue组件描述](#RedisQueue组件描述)
   + [ResQueue的目录结构](#ResQueue的目录结构)
   + [ResQueue使用前期准备](#ResQueue使用前期准备)



## RedisQueue组件描述
一个基于redis的队列项目，结合了symfony的console组件，采用命令创建队列、执行队列。
**redisQueue**是一个流程相对简单(相对那些重量级的MQ),具有以下几个特点

+ **易理解**
+ **使用简单**
+ **易维护**
+ **易扩展**
+ **容错机制完善**

总而言之,这个队列组件很灵活(有点自吹啊,但是真的很灵活,不骗你),为了方便大家使用,我将这个组件的使用步骤[写在这了](#)。



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
## 使用前期准备
### 使用`composer`安装组件
  + `symfony`的`console`命令组件

  + `phpmailer`组件

### 使用`symfony/console`组件运行队列
>以下是队列相关运行命令,首先进入到组件的根目录,使用artisan运行相关命令

+ 创建队列命令

```php
  php artisan queue:create

```
执行上面的创建命令,`redisQueue`会帮你创建默认的队列任务,默认的任务类为`SentEmailJob`。每个任务类中都有一个`perform`的方法,
队列进程主要是执行任务类中的这个方法来完成相应的队列任务。

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

+ 查看失败队列命令

```
php artisan queue:failed
```

+ 执行队列命令

```
php artisan queue:work
```
 
