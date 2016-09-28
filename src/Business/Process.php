<?php
/**
 * Created by PhpStorm.
 * User: lihan
 * Date: 16/9/26
 * Time: 11:19
 */
namespace FSth\SwooleDaemonize\Business;

use FSth\SwooleDaemonize\Tool\Logger;
use FSth\SwooleDaemonize\Tool\PidManager;

abstract class Process
{
    protected $pidManager;

    protected $logger;

    protected $name;

    protected $path;

    protected $config;

    public function __construct($name, $config)
    {
        $this->name = $name;
        $this->config = $config;
        $this->logger = new Logger(array('log_path' => $config['log']));
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function setPath($path)
    {
        $this->path = $path;
        $this->pidManager = new PidManager($path . "{$this->name}.pid");
    }

    public function start()
    {
        if ($this->pidManager->get()) {
            $this->stdout("Process {$this->name} already started");
            $this->logger->error("Process {$this->name} already started\n");
            return;
        }
        $this->stdout("Start process {$this->name} success");
        if (!empty($this->config['daemonize'])) {
            \swoole_process::daemon();
            $pid = posix_getpid();
            $this->pidManager->save($pid);
            $this->logger->info("Start {$this->name} as process {$pid}");
        }
        $this->execute();
    }

    public function stop()
    {
        if (!($pid = $this->pidManager->get())) {
            $this->stdout("Process {$this->name} is not running");
            $this->logger->warning("Process {$this->name} is not running");
            return;
        }
        if (!posix_kill($pid, SIGTERM)) {
            posix_kill($pid, SIGKILL);
        }
        $this->pidManager->clear();
        $this->stdout("Stop process {$this->name} {$pid}");
        $this->logger->info("Stop process {$pid}");
        $this->clearUp();
    }

    public function restart()
    {
        $this->stop();
        sleep(1);
        $this->start();
    }

    abstract protected function execute();

    protected function clearUp()
    {
    }

    protected function stdout($msg)
    {
        echo "{$msg}\n";
        return;
    }
}