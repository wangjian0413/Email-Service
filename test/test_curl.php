<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => 'api:key-3ax6xnjp29jd6fds4gc373sgvjxteol0',
    CURLOPT_URL => 'https://api.mailgun.net/v2/samples.mailgun.org/messages',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'from' => 'wangjian0413@hotmail.com',
        'to' => 'jianwangtest@gmail.com',
        'subject' => '111',
        'text' => 'this is jian'
    )
));

$resp = curl_exec($curl);

var_dump($resp);

curl_close($curl);