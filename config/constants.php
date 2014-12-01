<?php
/**
 * Constants definition
 */

class RESPONSE_CODE {
    const SUCCESS = 1;
    const INVALID_REQUEST = 2;
    const PROVIDER_FAILURE = 3;
    const INTERNAL_ERROR = 4;

    public static $description = array(
        self::SUCCESS => 'Success',
        self::INVALID_REQUEST => 'Invalid Request',
        self::PROVIDER_FAILURE => 'Provider Failure',
        self::INTERNAL_ERROR  => 'Internal Error',
    );
}

class PROVIDER_CONSTANTS
{
    const MAIL_GUN = 1;
    const MANDRILL = 2;
    public static $description = array(
        self::MAIL_GUN => 'Mail Gun',
        self::MANDRILL => 'Mandrill'
    );
}

final class HTTP_REQUEST_METHOD
{
    const GET   = 'GET';
    const POST  = 'POST';
}

class MANDRILL_RESPONSE_STATUS
{
    const SENT     = 'sent';
    const REJECTED = 'rejected';
    const QUEUED   = 'queued';
    const INVALID  = 'invalid';
}

class MAILGUN_RESPONSE_STATUS
{
    const SUCCESS = '200';
    const BAD_REQUEST = '400';
    const UNAUTHORIZED = '401';
    const REQUEST_FAILED = '402';
    const NOT_FOUND = '404';
    const SERVER_ERROR1 = '500';
    const SERVER_ERROR2 = '502';
    const SERVER_ERROR3 = '503';
    const SERVER_ERROR4 = '504';

}