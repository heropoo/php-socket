<?php
/**
 * Single http server.
 *
 *
 * @license Apache-2.0
 * @author farwish
 */

$s_socket_uri = 'tcp://0.0.0.0:8888';
$s_socket = stream_socket_server($s_socket_uri, $errno, $errstr) OR
    trigger_error("Failed to create socket: $s_socket_uri, Err($errno) $errstr", E_USER_ERROR);

while(1)
{
    while($connection = @stream_socket_accept($s_socket, 30, $peer))
    {
        echo "Connected with $peer.  Request info...\n";

        $client_request = "";
        // Read until double \r
        while( !preg_match('/\r?\n\r?\n/', $client_request) )
        {
            $client_request .= fread($connection, 1024);
        }

        if (!$client_request)
        {
            trigger_error("Client request is empty!");
        }

        echo $client_request;

        $headers = "HTTP/1.1 200 OK\r\n"
            ."Server: nginx\r\n"
            ."Content-Type: text/html; charset=utf-8\r\n"
            ."\r\n";
        $body = "<h1>hello world</h1><br><br>";
        if ((int) fwrite($connection, $headers . $body) < 1) {
            trigger_error("Write to socket failed!");
        }
        fclose($connection);
    }
    echo '-'.PHP_EOL;
}