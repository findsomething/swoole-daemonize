<?php
/**
 * Created by PhpStorm.
 * User: lihan
 * Date: 16/9/26
 * Time: 11:36
 */
namespace FSth\SwooleDaemonize\Business;

abstract class TimeProcess extends Process
{
    protected $frequency = 1000;

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    protected function execute()
    {
        \swoole_timer_tick($this->frequency, function(){
           $this->process();
        });
    }

    abstract protected function process();
}