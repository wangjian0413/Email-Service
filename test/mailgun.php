<?php

require_once(SERVICE_ROOT . '/clients/RESTclient.php');

function createEmailReq($from, $to, $subject, $text) {
    $req = array(
    'from' => $from,
    'to' => $to,
    'subject' => $subject,
    'text' => $text
    );
    return $req;
}

$client = new RESTClient('https://api.mailgun.net/v2/samples.mailgun.org/messages', 'POST');

$client->setCurlOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$client->setCurlOption(CURLOPT_USERPWD, 'api:key-3ax6xnjp29jd6fds4gc373sgvjxteol0');

$body = createEmailReq("wangjian0413@hotmail.com", "jianwangtest@gmail.com,kekedouer@gmail.com", "Test, Please", "hi, my name is jian.");
$client->sendRequest(null, $body);

$resp =  $client->getResponseBody();

var_dump($resp);
