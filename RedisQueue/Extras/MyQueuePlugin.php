<?php

namespace RedisQueue\Extras;

use RedisQueue\ReQueue\Event;

// Somewhere in our application, we need to register:
Event::listen('afterEnqueue', array('MyQueuePlugin', 'afterEnqueue'));
Event::listen('beforeFirstFork', array('MyQueuePlugin', 'beforeFirstFork'));
Event::listen('beforeFork', array('MyQueuePlugin', 'beforeFork'));
Event::listen('afterFork', array('MyQueuePlugin', 'afterFork'));
Event::listen('beforePerform', array('MyQueuePlugin', 'beforePerform'));
Event::listen('afterPerform', array('MyQueuePlugin', 'afterPerform'));
Event::listen('onFailure', array('MyQueuePlugin', 'onFailure'));

class MyQueuePlugin
{
	public static function afterEnqueue($class, $arguments)
	{
		echo "Job was queued for " . $class . ". Arguments:";
		print_r($arguments);
	}
	
	public static function beforeFirstFork($worker)
	{
		echo "Worker started. Listening on queues: " . implode(', ', $worker->queues(false)) . "\n";
	}
	
	public static function beforeFork($job)
	{
		echo "Just about to fork to run " . $job;
	}
	
	public static function afterFork($job)
	{
		echo "Forked to run " . $job . ". This is the child process.\n";
	}
	
	public static function beforePerform($job)
	{
		echo "Cancelling " . $job . "\n";
	//	throw new Resque_Job_DontPerform;
	}
	
	public static function afterPerform($job)
	{
		echo "Just performed " . $job . "\n";
	}
	
	public static function onFailure($exception, $job)
	{
		echo $job . " threw an exception:\n" . $exception;
	}
}