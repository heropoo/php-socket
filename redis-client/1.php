<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/7/3
 * Time: 16:54
 */

require_once __DIR__.'/Connection.php';
require_once __DIR__.'/SocketException.php';
require_once __DIR__.'/Exception.php';

$redis = new \Moon\Redis\Connection();
$redis->hostname = '116.62.214.14';
$redis->password = 'Test86315993';
$aaa = $redis->executeCommand('get', ['aaa']);
var_dump($aaa);