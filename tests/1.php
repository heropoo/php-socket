<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/6/8
 * Time: 13:51
 */

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_bind($sock, '127.0.0.1', 8080);

socket_listen($sock);

while (true){
    $conn = socket_accept($sock);
    $write_buff = "HTTP/1.1 200 OK\r\nServer: My Server\r\nContent-Type: text/html; charset=utf-8\r\n\r\nHello World!";
    socket_write($conn, $write_buff);
}
socket_close($sock);



