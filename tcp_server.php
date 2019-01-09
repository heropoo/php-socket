<?php
declare(ticks = 1);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$ip = '0.0.0.0';
$port = 8888;

//绑定接收的套接流主机和端口
if(socket_bind($socket, $ip, $port) === false){
    exit('Server bind fail: '.socket_strerror(socket_last_error()));
}

//监听套接流
if(socket_listen($socket, 4) === false) {
    exit('Server listen fail: '.socket_strerror(socket_last_error()));
}

echo "Server listening on $ip:$port ...\n";

$client_id = 0;

//让服务器无限获取客户端传过来的信息
while(true){
    //接收客户端传过来的信息
    $accept_resource = socket_accept($socket);
    if($accept_resource !== false){
        //读取客户端传过来的资源，并转化为字符串
        $string = socket_read($accept_resource, 1024);
        if($string !== false){
            echo "Server received [from client $client_id]: ".$string.PHP_EOL;

            $return_client = 'Server received is: '.$string.PHP_EOL;

            //向socket_accept的套接流写入信息 反馈给客户端
            socket_write($accept_resource, $return_client, strlen($return_client));
        }else{
            echo 'ocket_read failed';
        }
        $client_id++;
        socket_close($accept_resource);
    }
}