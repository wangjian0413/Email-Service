<?php

require_once(SERVICE_ROOT . '/clients/RESTclient.php');

$client = new RESTClient('https://mandrillapp.com/api/1.0/messages/send.json', 'POST');

$body = array(
    'key' => 'LFbNi6pttEZYje5h-9cazw',
    'message' => array(
        'text' => 'newtesttesttest',
        'subject' => 'this is new test2',
        'from_email' => 'wangjian0413@hotmail.com',
        'to' => array(
            array(
            'email' => 'jianwangtest@gmail.com',
            'type' => 'to'
            )
        )
    ),
);

$test = json_encode($body);

$client->sendRequest(null, $test);

print_r($client);
$resp =  $client->getResponseBody();

var_dump(json_decode($resp));
