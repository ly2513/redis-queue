# redisQueue
一个基于redis的队列项目，结合了symfony的console组件，采用命令创建队列、执行队列

`redisQueue`队列库是基于redis队列实现的一套队列任务处理机制，同时提供了`Symfony`的`command`组件的队列命令.

## 队列使用说明
+ 创建队列命令

```
  queue:create
```

+ 查看失败队列命令

```
queue:failed
```

+ 执行队列命令

```
queue:work
```
 
