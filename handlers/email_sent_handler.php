<?php

require_once(PROJECT_ROOT . '/email_request/rest_response.php');
require_once(PROJECT_ROOT . '/handlers/abstract_service_handler.php');
require_once(SERVICE_ROOT . '/providers/mail_gun_provider.php');
require_once(SERVICE_ROOT . '/providers/mandrill_provider.php');
require_once(PROJECT_ROOT . '/email_request/messages.php');

/**
 * Class EmailSentHandler extends from AbstractServiceHandler and is used for sending emails.
 */
class EmailSentHandler extends AbstractServiceHandler{

    public function execute(RestRequest $req) {

        if(! $req instanceof SendEmailRequest) {
            return new RestResponse(RESPONSE_CODE::INVALID_REQUEST, RESPONSE_CODE::$description[RESPONSE_CODE::INVALID_REQUEST]);
        }

        $trialSequence = $this->determineSequence();
        $success = false;
        $errorMessage = '';
        $trialProvider = array();
        foreach($trialSequence as $p) {
            $provider = new $p;
            if(!$provider instanceof Provider) {
                return new RestResponse(RESPONSE_CODE::INTERNAL_ERROR, RESPONSE_CODE::$description[RESPONSE_CODE::INVALID_REQUEST]);
            }

            $trialProvider[] = PROVIDER_CONSTANTS::$description[$provider->providerId];

            $msg = new Message($req->params);
            if($provider->sendEmail($msg)) {
                $success = true;

                break;
            };

            $errorMessage = $provider->responseMessage;
        }

        if($success) {
            return new RestResponse(RESPONSE_CODE::SUCCESS, RESPONSE_CODE::$description[RESPONSE_CODE::SUCCESS], implode(',', $trialProvider));
        } else {
            return new RestResponse(RESPONSE_CODE::PROVIDER_FAILURE, $errorMessage, implode(',', $trialProvider));
        }
    }

    /**
     * Define a email provider shuffled sequence that used for sending emails in order.
     * @return array contains ordered email provider name.
     */
    private function determineSequence() {

        $sequence = array(
            'MandrillProvider',
            'MailGunProvider',
        );

        shuffle($sequence);

        return $sequence;
    }
}
