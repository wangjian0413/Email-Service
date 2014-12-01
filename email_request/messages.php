<?php
/**
 * Message contains email sending related messages that used for provider to send email
 */

class Message
{
    protected $subject;
    protected $text;
    protected $from;
    protected $to;
    protected $cc;
    protected $bcc;

    public function __construct(array $msg) {
        $this->subject = $msg['subject'];
        $this->from = $msg['from'];
        $this->to = $msg['to'];
        $this->text = $msg['text'];
        $this->cc   = $msg['cc'];
        $this->bcc  = $msg['bcc'];
    }
    public function getSubject() {
        return $this->subject;
    }
    public function getText() {
        return $this->text;
    }
    public function getFrom() {
        return $this->from;
    }
    public function getTo() {
        return $this->to;
    }

    public function getCC() {
        return $this->cc;
    }

    public function getBCC() {
        return $this->bcc;
    }
}
