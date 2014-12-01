<?php
/**
 * Abstract class for email sending providers
 */

abstract class Provider
{
    public $url;
    public $key;
    public $providerId;

    public $responseMessage;
    public $responseStatus;
    public $responseBody;

    /** Define sending email action for providers.
     * @param Message $message
     * @return mixed
     */
    abstract public function sendEmail(Message $message);

}