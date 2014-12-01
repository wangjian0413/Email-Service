<?php
/**
 * Mandrill provider https://mandrillapp.com/docs/
 */
require_once(SERVICE_ROOT . '/providers/abstract_provider.php');
require_once(SERVICE_ROOT . '/clients/RESTclient.php');

class MandrillProvider extends Provider
{
    const URL = MANDRILL_PROVIDER_URL;
    const KEY = MANDRILL_PROVIDER_KEY;
    public $providerId = PROVIDER_CONSTANTS::MANDRILL;

    public function __construct() {
    }

    /** Curl call Mandrill Provider service to send email
     * @param Message $message
     * @return bool whether sending email is successful or not.
     */
    public function sendEmail(Message $message) {
        $client = new RESTClient(MandrillProvider::URL, HTTP_REQUEST_METHOD::POST);
        $body = array();
        $body['key'] = MandrillProvider::KEY;
        $body['message'] = array();
        $body['message']['text'] = $message->getText();
        $body['message']['subject'] = $message->getSubject();
        $body['message']['from_email'] = $message->getFrom();

        $body['message']['to'] = array();
        $toEmails = explode(',', $message->getTo());
        foreach($toEmails as $d) {
            $body['message']['to'][] = array(
                'email' => $d,
                'type' => 'to'
            );
        }

        $ccEmails = $message->getCC();;
        if(!empty($ccEmails)) {
            $ccEmails = explode(',', $ccEmails);
            foreach($ccEmails as $d) {
                $body['message']['to'][] = array(
                    'email' => $d,
                    'type' => 'cc'
                );
            }
        }

        $bccEmails = $message->getBCC();
        if(!empty($bccEmails)) {
            $bccEmails = explode(',', $bccEmails);
            foreach($bccEmails as $d) {
                $body['message']['to'][] = array(
                    'email' => $d,
                    'type' => 'bcc'
                );
            }
        }

        $client->sendRequest(null, json_encode($body));

        $this->responseStatus = $client->getResponseCode();
        $this->responseBody = json_decode($client->getResponseBody());

        $result = true;
        if($this->responseStatus == 200) {
            $result =  isset($this->responseBody[0]) && $this->responseBody[0]->status == MANDRILL_RESPONSE_STATUS::SENT;
            $this->responseMessage = isset($this->responseBody[0]) ? $this->responseBody[0]->reject_reason : null;
        } else {
            $result = false;
            $this->responseMessage = $this->responseBody->message;

        }

        return $result;
    }
}