如何使用RedisQueue见根目录下的[README.md](https://github.com/ly2513/redisQueue/blob/master/README.md).

以下要讲解是RedisQueue如何运作流程。

## 入队任务
当我们调用`ResQueue::enqueue`这方法,它具体是怎么运行的了?带着这问题,我详细向大家讲解下,主要分以下步骤:

 + `ResQueue::enqueue()`通过相同的参数传入调用`Job::create()`进行创建任务。
 + `Job::create()`方法会检测传入进来的参数变量--`$args`(第三个参数),要么为`null`要么为数组。
 + 然后`Job::create()`方法会生成一个任务ID(在多数文档中的成为'令牌'),主要用于区分任务,作为唯一标识。
 + 接着`Job::create()`方法将任务推送到请求的队列中(第一个参数)
 + 如果任务作业为启用状态监视时`Job::create()`方法(第四参数),会通过传入任务ID唯一个参数调用`Status::create()`进行初始化任务状态。
 + `Status::create()`会通过任务ID在redis中创建一个key,当前的任务状态作为这个key的值,将返回来控制`Job::create()`。
 + `Job::create()`通过任务ID作为返回值进而控制`ResQueue::enqueue()`。
 + `ResQueue::enqueue()`触发`afterEnqueue`事件,从而控制你的应用程序,将任务ID再一次作为返回值返回。

 ## worker进程如何工作的

 ###worker进程如何工作的了?
 + `Worker::work()`,worker进程会调用Worker->reserve()这方法去检查队列里面是否有待处理的任务。

 + `Worker->reserve()`会检查是否使用阻挡弹出，然后采取相应的行动:
    + 阻止弹出
        + `Worker->reserve()`通过传入整个对列列表和超时的间隔这两个参数调用`Job::reserveBlocking()`;
        + `Job::reserveBlocking()`调用`Job::reserve()`(这就需要使用“`blpop`，之后准备呼叫队列列表中，然后处理响应与图书馆其他方面的一致性，最后返回控制[和检索工作队列的内容，如果有`Job::reserveBlocking()`)
        + `Job::reserveBlocking()`检查任务内容是一个数组(它应该包含任务类[class],payload[args],and ID),如果不是一个数组或缺少参数将终止处理。
        + `Job::reserveBlocking()`实例化一个Job对象，并返回调用`Worker->reserve()`;

    + 等待处理队列
        + `Worker->reserve()`遍历队列列表，传入当前队列的名称作为唯一参数调用`Job::reserve()方法;
        + `Job::reserve()`方法通过传入队列名称调用`ResQueue::pop()`方法进而调用`Redis`的`LPOP`命令进行队列任务出队,然后返回调用执行`Job::reserve()`;
        + `Job::reserve()`检查Job内容是一个数组（如前，它应该包含作业类型[类],payload[args],ID），如果不是一个数组或缺少参数将终止处理。
        + `Job::reserve()`用上述相同的方式实例化一个Job对象,然后调用`Worker->reserve()`方法。

 + 在任何情况下,`Worker->reserve()`返回新的Job对象，随着调用`Worker::work()`方法；如果没有找到需要处理的Job任务，就返回false
    + 没有任务时
        + 如果阻塞模式未启用,`worker::work()`休眠间隔时间为秒,反复进行检查是否有待处理的队列任务;
        + 存储队列任务
            + `Worker::work()` 触发一个`beforeFork`事件;
            + `Worker::work()`通过实例化一个Job对象作为参数调用`Worker->workingOn()`处理新的任务;
            + `Worker->workingOn()`进行跟踪任务相对应的work进程或者是与work进程之间的关系，然后从等待到运行更新任务的状态;
            + `Worker->workingOn()`将新Job对象的有效载荷在给Work本身相关的Redis的关键（这是为了防止Job被丢失，在不依赖相对应的PID的时候），然后将结果返回给`Worker::work()`;
            + `Worker::work()`接下来通过fork子进程来运行实际的`perform()`;
            + 下一步就是不同的Work的子进程作为独立的进程运行;
                + Work运行步骤
                    + Work等待Job进程的完成;
                    + 如果退出时状态不是0，Work会传入一个`Job_DirtyExitException`对象作为唯一的参数去调用`Job->fail()`;
                    + `Job->fail()`触发`onFailure`事件(故障事件);
                    + `Job->fail()`更新这个任务状态(从运行到失败这样的状态记录);
                    + 同时`Job->fail()`通过任务的一些参数调用`Failure::create()`,从而通过work的ID和队列名称作为参数调用`Job_DirtyExitException`(抛出任务异常);
                    + `Failure::create()`创建一个已经被`Failure "backend"`处理的任何类型的对象,默认情况下，`Failure_redis`的构造函数只是收集数据传递到`Failure::create`,然后推到失败的队列中;
                    + `Job->fail()`会在redis中设置两个增量计数器：一个redis失败的总数，一个Work失败的总数;
                    + `Job->fail()`返回控制给Work（此时Work程序仍在运行;
                + Job运行步骤
                    + 任务会通过`Job`实例作为唯一的参数去调用`Worker->perform()`
                    + `Worker->perform()`设置一个`try…catch`模块可以适当的标记工作失败的异常处理（通过调用resque_job -> fail()，如上）