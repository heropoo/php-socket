<?php

$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'0.0.0.0',8888) or die('error');
socket_listen($socket,5);

while(true){
    $client = socket_accept($socket);

    //创建紫金城
    $pid = pcntl_fork();
    //父进程和子进程都会执行下面代码
    if ($pid == -1) {
        //错误处理：创建子进程失败时返回-1.
        die('could not fork');
    } else if ($pid) {
        //父进程会得到子进程号，所以这里是父进程执行的逻辑
        pcntl_wait($status, WNOHANG); //等待子进程中断，防止子进程成为僵尸进程。
        socket_close($client);
    } else {
        //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
        $buf = socket_read($client,1024);
        echo $buf;
        if(preg_match('/sleep/i',$buf)){
            sleep(10);
            $html = "HTTP/1.1 200 OK\r\n"
                ."Content-Type: text/html;charset=utf-8\r\n\r\n";
            socket_write($client,$html);
            socket_write($client,'this is server,休克了10秒,模拟很繁忙的样子');
        }else{
            $html = "HTTP/1.1 200 OK\r\n"
            ."Content-Type: text/html;charset=utf-8\r\n\r\n";
            socket_write($client,$html.'this is server');
        }
        socket_close($client);
        exit();
    }
}

socket_close($socket);