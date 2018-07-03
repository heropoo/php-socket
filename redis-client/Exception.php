<?php
/**
 * Created by PhpStorm.
 * User: ttt
 * Date: 2018/7/3
 * Time: 16:44
 */

namespace Moon\Redis;


use Throwable;

class Exception extends \Exception
{
    public function __construct($message = "", $errorDescription = '', $code = 0, Throwable $previous = null)
    {
        $message .= $errorDescription ? ' - '.$errorDescription : '';
        parent::__construct($message, $code, $previous);
    }
}