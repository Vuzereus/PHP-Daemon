<?php

class Core_Worker_Process
{

    public $pid;
    public $alias;
    public $microtime;
    public $job;
    public $timeout = 60;
    private $stop_time = null;

    public function runtime() {
        return microtime(true) - $this->microtime;
    }

    public function running(Core_Worker_Call $call) {
        $this->calls[] = $call->id;
    }

    /**
     * Stop the process, using whatever means necessary, and possibly return a textual description
     * @return bool|string
     */
    public function stop() {

        if (!$this->stop_time) {
            $this->stop_time = time();
            @posix_kill($this->pid, SIGTERM);
            return "Worker Process '{$this->pid}' Timeout: Killing...";
        }

        if (time() > $this->stop_time + $this->timeout) {
            @posix_kill($this->pid, SIGKILL);
            return "Worker Process '{$this->pid}' Timeout: Killing...";
        }

        return null;
    }
}