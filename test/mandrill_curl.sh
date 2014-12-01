#!/bin/bash

curl -A 'Mandrill-Curl/1.0' \
-d '{"key":"LFbNi6pttEZYje5h-9cazw", "message":{"text":"This is testing","subject":"Im testing","from_email":"wangjian0413@hotmail.com","to":[{"email":"jianwangtest@gmail.com","type":"to"}]}}' \
'https://mandrillapp.com/api/1.0/messages/send.json'