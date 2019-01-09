<?php

$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'0.0.0.0',8888) or die('error');
socket_listen($socket,5);

echo "========================= START =================================" . PHP_EOL;
$mainPid = getmypid(); //主进程id
$processNum = 0;
$processMax = 10; //最大进程数


$index = 0;

while (true) {
    $client = socket_accept($socket);

    //开始产生子进程
    $pid = pcntl_fork();

    switch ($pid) {
        case -1:
            echo "Fork failed!" . PHP_EOL;
            break;
        case 0:
            try {
                $childPid = getmypid(); //前进程的PID
                echo "FORK: Child ChildPid:#{$childPid} is running..." . PHP_EOL;
                
                //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
                $buf = socket_read($client,1024);
                //echo $buf;
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
            
                echo "FORK: Child ChildPid:#{$childPid} is exit [{$name}] res:{$res}" . PHP_EOL;
            } catch (\Throwable $e) {
                echo "[{$name}]{$e->getFile()} line:{$e->getLine()} code:{$e->getCode()} message:{$e->getMessage()}" . PHP_EOL;
            }
            //子进程要exit否则会进行递归多进程，父进程不要exit否则终止多进程
            exit($childPid);
            break;
        default:
            // fork成功，并且父进程会进入到这里
            if ($index == 0) {
                echo "Parent #{$mainPid} is running..." . PHP_EOL;
            }
            $processNum++;
            if ($processNum>=$processMax){
                pcntl_wait($status);
                echo "I am waiting ...#{$index}" . PHP_EOL;
                $processNum--;
            }
            socket_close($client);
    }
    $index++;
}

//父进程利用while循环，并且通过pcntl_waitpid函数来等待所有子进程完成后才继续向下进行
while (pcntl_waitpid(0, $status) != -1) {
    //pcntl_wexitstatus返回一个中断的子进程的返回代码，由此可判断是哪一个子进程完成了
    $status = pcntl_wexitstatus($status);
    echo "===> Child #{$status} has completed!" . PHP_EOL;
}
echo ">>>>> Parent #{$mainPid} has completed! <<<<<<" . PHP_EOL;
echo "========================= END ===================================" . PHP_EOL;