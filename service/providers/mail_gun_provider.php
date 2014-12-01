<?php
/**
 * Mailgun provider by rackspace. http://documentation.mailgun.com/
 */

require_once(SERVICE_ROOT . '/providers/abstract_provider.php');
require_once(SERVICE_ROOT . '/clients/RESTclient.php');

class MailGunProvider extends Provider
{
    const URL = MAIL_GUN_PROVIDER_URL;
    const KEY = MAIL_GUN_PROVIDER_KEY;
    public $providerId = PROVIDER_CONSTANTS::MAIL_GUN;


    public function __construct() {
    }

    /** Curl call Mail Gun Provider service to send email.
     * @param Message $message
     * @return bool whether sending email is successful or not.
     */
    public function sendEmail(Message $message){
        $client = new RESTClient(MailGunProvider::URL, HTTP_REQUEST_METHOD::POST);

        $client->setCurlOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $client->setCurlOption(CURLOPT_USERPWD, MailGunProvider::KEY);

        $body = array(
        'from' => $message->getFrom(),
        'to' => $message->getTo(),
        'subject' => $message->getSubject(),
        'text' => $message->getText(),
        );

        $cc = $message->getCC();
        if(!empty($cc)) {
            $body['cc'] = $cc;
        }
        $bcc = $message->getBCC();
        if(!empty($bcc)) {
            $body['bcc'] = $bcc;
        }

        $client->sendRequest(null, $body);
        $this->responseBody = json_decode($client->getResponseBody());
        $this->responseMessage = isset($this->responseBody) ? $this->responseBody->message : null;
        $this->responseStatus = $client->getResponseCode();

        return $this->responseStatus == MAILGUN_RESPONSE_STATUS::SUCCESS;
    }
}