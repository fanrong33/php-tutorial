<?php
/**
 * php中Redis函数的用法总结
 * 
 * http://www.php.cn/php-weizijiaocheng-380858.html
 */

$redis = new Redis(); 
$redis->connect('127.0.0.1', 6379) or die("Could not connect redis"); 
$effect = $redis->auth('123456');  // 登录验证密码，返回 true、false
var_dump($effect);

$redis->set('key','123');
var_dump($redis->get('key'));

$redis->delete('key');
var_dump($redis->get('key'));

$redis->setex('key2', 10, 'value');
var_dump($redis->get('key2'));


$redis->close();//释放资源
