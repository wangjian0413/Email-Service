#!/bin/bash

curl -s --user 'api:key-3ax6xnjp29jd6fds4gc373sgvjxteol0' \
    https://api.mailgun.net/v2/samples.mailgun.org/messages \
    -F from='Excited User <kekedouer@gmail.com>' \
    -F to=jianwangtest@gmail.com \
    -F subject='Hello' \
    -F text='Testing some Mailgun awesomness!'