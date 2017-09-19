<?php
/**
 * Redisent, a Redis interface for the modest
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/28
 * Time: 下午1:04
 * Email: liyong@addnewer.com
 */
namespace RedisQueue\RedisSent;

use Exception;
use RedisException;

define('CRLF', sprintf('%s%s', chr(13), chr(10)));

/**
 * Class RedisSent a Redis interface for the modest among us
 *
 * @package RedisQueue\RedisSent
 */
class RedisSent
{

    /**
     * Socket connection to the Redis server
     * @var resource
     * @access private
     */
    private $__sock;

    /**
     * Host of the Redis server
     * @var string
     * @access public
     */
    public $host;

    /**
     * Port on which the Redis server is running
     * @var integer
     * @access public
     */
    public $port;

    /**
     * Creates a RedisSent connection to the Redis server on host and port.
     *
     * @param string  $host The hostname of the Redis server
     * @param integer $port The port number of the Redis server
     */
    public function __construct($host, $port = 6379)
    {
        $this->host = $host;
        $this->port = $port;
        $this->establishConnection();
    }

    public function establishConnection()
    {
        $this->__sock = fsockopen($this->host, $this->port, $errno, $errstr);
        if (!$this->__sock) {
            throw new Exception("{$errno} - {$errstr}");
        }
    }

    public function __destruct()
    {
        fclose($this->__sock);
    }

    public function __call($name, $args)
    {
        // Build the Redis unified protocol command
        array_unshift($args, strtoupper($name));
        $command = sprintf('*%d%s%s%s', count($args), CRLF, implode(array_map([$this, 'formatArgument'], $args), CRLF),
            CRLF);
        // Open a Redis connection and execute the command
        for ($written = 0; $written < strlen($command); $written += $fwrite) {
            $fwrite = fwrite($this->__sock, substr($command, $written));
            if ($fwrite === false) {
                throw new Exception('Failed to write entire command to stream');
            }
        }
        // Parse the response based on the reply identifier
        $reply = trim(fgets($this->__sock, 512));
        switch (substr($reply, 0, 1)) {
            // Error reply
            case '-':
                throw new RedisException(substr(trim($reply), 4));
                break;
            // Inline reply
            case '+':
                $response = substr(trim($reply), 1);
                break;
            // Bulk reply
            case '$':
                $response = null;
                if ($reply == '$-1') {
                    break;
                }
                $read = 0;
                $size = substr($reply, 1);
                do {
                    $block_size = ($size - $read) > 1024 ? 1024 : ($size - $read);
                    $response .= fread($this->__sock, $block_size);
                    $read += $block_size;
                } while ($read < $size);
                fread($this->__sock, 2); // discard crlf
                break;
            // Multi-bulk reply
            case '*':
                $count = substr($reply, 1);
                if ($count == '-1') {
                    return null;
                }
                $response = [];
                for ($i = 0; $i < $count; $i++) {
                    $bulk_head = trim(fgets($this->__sock, 512));
                    $size      = substr($bulk_head, 1);
                    if ($size == '-1') {
                        $response[] = null;
                    } else {
                        $read  = 0;
                        $block = "";
                        do {
                            $block_size = ($size - $read) > 1024 ? 1024 : ($size - $read);
                            $block .= fread($this->__sock, $block_size);
                            $read += $block_size;
                        } while ($read < $size);
                        fread($this->__sock, 2); /* discard crlf */
                        $response[] = $block;
                    }
                }
                break;
            // Integer reply
            case ':':
                $response = intval(substr(trim($reply), 1));
                break;
            default:
                throw new RedisException("invalid server response: {$reply}");
                break;
        }

        // Party on
        return $response;
    }

    private function formatArgument($arg)
    {
        return sprintf('$%d%s%s', strlen($arg), CRLF, $arg);
    }
}