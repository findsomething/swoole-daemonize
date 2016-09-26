<?php
/**
 * Created by PhpStorm.
 * User: lihan
 * Date: 16/9/26
 * Time: 14:54
 */
namespace FSth\SwooleDaemonize\Demo;

use FSth\SwooleDaemonize\Business\TimeProcess;

class DemoProcess extends TimeProcess
{
    protected $frequency = 1000;
    
    protected function process()
    {
        echo "hello world";
        $this->logger->info('hello world');
    }
}