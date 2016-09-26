<?php
/**
 * Created by PhpStorm.
 * User: lihan
 * Date: 16/9/26
 * Time: 11:20
 */
namespace FSth\SwooleDaemonize\Tool;

class PidManager
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function get()
    {
        if (!file_exists($this->path)) {
            return 0;
        }
        return intval(file_get_contents($this->path));
    }

    public function save($pid)
    {
        $pid = intval($pid);
        file_put_contents($this->path, $pid);
    }

    public function clear()
    {
        if (!file_exists($this->path)) {
            return;
        }

        unlink($this->path);
    }
}