<?php
/**
 * Config definition
 */

define("PROJECT_ROOT",  dirname(__FILE__) . '/..');
define("SERVICE_ROOT",  dirname(__FILE__) . '/../service');

define("MAIL_GUN_PROVIDER_URL",  'https://api.mailgun.net/v2/samples.mailgun.org/messages');
define("MAIL_GUN_PROVIDER_KEY",  'api:key-3ax6xnjp29jd6fds4gc373sgvjxteol0');

define("MANDRILL_PROVIDER_URL", 'https://mandrillapp.com/api/1.0/messages/send.json');
define("MANDRILL_PROVIDER_KEY", 'LFbNi6pttEZYje5h-9cazw');

require_once(PROJECT_ROOT . '/config/constants.php');