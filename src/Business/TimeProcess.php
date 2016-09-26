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
    protected $processConfig;

    public function __construct($name, $config)
    {
        parent::__construct($name, $config);
        if (!empty($config['processes']) && !empty($config['processes'][$name])) {
            $this->processConfig = $config['processes'][$name];
        }
        $this->setUp();
    }

    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    protected function execute()
    {
        \swoole_timer_tick($this->frequency, function () {
            $this->process();
        });
    }

    protected function setUp()
    {
        if (empty($this->processConfig)) {
            return;
        }
        if (!empty($this->processConfig['frequency'])) {
            $this->frequency = $this->processConfig['frequency'];
        }
    }

    abstract protected function process();
}