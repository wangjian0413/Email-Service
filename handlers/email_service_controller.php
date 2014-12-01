<?php
require_once(PROJECT_ROOT . '/email_request/rest_request.php');

/**
 * Class EmailServiceController is used to call email sending process and return response code.
 */
class EmailServiceController
{
    /**
     * Send emails based on request and return response.
     * @param $req request object
     * @param $res response object
     */
    public function sendEmail($req, $res) {

        require_once(PROJECT_ROOT . '/handlers/email_sent_handler.php');

        $sendEmailReq = new SendEmailRequest();

        $rs = $sendEmailReq->parseRequest($req);
        if($rs !== true) {
            $rs = new RestResponse(RESPONSE_CODE::INVALID_REQUEST, $rs);
        } else {
            $handler = new EmailSentHandler();
            $rs = $handler->execute($sendEmailReq);
        }

        $res->add(json_encode($rs));
        if($rs->status == RESPONSE_CODE::SUCCESS) {
            $res->send(200, 'json');
        } else if ($rs->status == RESPONSE_CODE::INVALID_REQUEST) {
            $res->send(400, 'json');
        } else {
            $res->send(500, 'json');
        }
    }
}
