<?php

namespace App\Services;


class Sender
{
    protected $response;
    protected $debug = false;

    public function __construct()
    {
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    protected function log($message)
    {
        if ($this->debug) {
            echo $message . "\n";
        }
    }

    /**
     * Sender email.
     */
    public function request()
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->log('i = ' . $i);
        }
    }
}
