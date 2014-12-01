<?php
/**
 * Class RestResponse
 */
class RestResponse
{
    public $status;
    public $body;
    public  $message;
    public $info;

    public function __construct($status, $message = '', $info = '', $body = null) {
        $this->status = $status;
        $this->message = $message;
        $this->body = $body;
        $this->info = $info;
    }
}