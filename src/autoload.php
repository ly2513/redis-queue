<?php
/**
 * Created by IntelliJ IDEA.
 * User: yongli
 * Date: 16/9/29
 * Time: 下午1:56
 * Email: liyong@addnewer.com
 */
if (!defined('ROOT_PATH')) {
    die('Access Denied');
}

/**
 * 注册加载的类
 *
 * @param $class
 *
 * @return bool
 */
function register_lass($class)
{
    if (strpos('\\', $class) !== false) {
        return false;
    }
    // 加载组件的所有类
    $class = str_replace('\\', '/', $class) . '.php';
    // 加载队列
    $queueFiles = LIB_PATH . $class;
    if (file_exists($queueFiles)) {
        require $queueFiles;
    }
    spl_autoload_register('load_class');

    return true;
}

/**
 * 加载类
 *
 * @param $class
 *
 * @return bool
 */
function load_class($class)
{
    $class       = trim($class, '\\');
    $class       = str_ireplace('.php', '', $class);
    $mapped_file = load_in_namespace($class);

    return $mapped_file;
}

/**
 * 为给定类名加载类文件
 *
 * @param $class
 *
 * @return bool
 */
function load_in_namespace($class)
{
    if (strpos($class, '\\') === false) {
        return false;
    }
    $namespaceMap = [
        'Con' => LIB_PATH . 'Config',
    ];
    foreach ($namespaceMap as $namespace => $directories) {
        if (is_string($directories)) {
            $directories = [$directories];
        }
        foreach ($directories as $directory) {
            if (strpos($class, $namespace) === 0) {
                $filePath = $directory . str_replace('\\', '/', substr($class, strlen($namespace))) . '.php';
                $filename = require_file($filePath);
                if ($filename) {
                    return $filename;
                }
            }
        }
    }

    // 没找到映射文件
    return false;
}

/**
 * 加载文件
 *
 * @param $file 需加载的文件
 *
 * @return bool|string
 */
function require_file($file)
{
    $file = sanitize_filename($file);
    if (file_exists($file)) {
        require_once $file;

        return $file;
    }

    return false;
}

/**
 * 规范化文件名，移除非法字符使用破折号代替。
 *
 * @param string $filename 需要规范的文件
 *
 * @return string
 */
function sanitize_filename(string $filename): string
{
    $filename = preg_replace('/[^a-zA-Z0-9\s\/\-\_\.\:\\\\]/', '', $filename);
    // 清理我们的文件名扩展
    $filename = trim($filename, '.-_');

    return $filename;
}

spl_autoload_register('register_lass');

