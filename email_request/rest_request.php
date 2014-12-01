<?php
/**
 * Class RestRequest is an abstract class for all REST request related.
 */
abstract class RestRequest
{
    public $params;
    protected $requiredParams = array();
    protected $optionalParams = array();

    /**
     * Parse a request and validate some required params.
     * @param $req request object
     * @return bool|string if missing required params, return error msg; otherwise return true
     */
    public function parseRequest($req) {
        //check required params
        $rs = $this->validateRequiredParams($req);
        if(!empty($rs)) {
            //return error
            return 'The following params are missing : ' . implode(',', $rs);
        }

        $params = $this->parseRequestParams($req);
        $this->params = $params;

        return true;
    }

    /**
     * Validate a request
     * @param $rawRequest request object
     * @return array return missing params if there is any.
     */
    protected function validateRequiredParams($rawRequest) {
        $rawData = $rawRequest->data;
        $missingParams = array();

        foreach($this->requiredParams as $key) {
            if(!isset($rawData[$key])) {
                $missingParams [] = $key;
                continue;
            }
        }

        return $missingParams;
    }

    /**
     * Parse a request.
     * @param $rawRequest
     * @return array
     */
    protected  function parseRequestParams($rawRequest) {
        $rawData = $rawRequest->data;
        $params = array();

        foreach($rawData as $key => $value) {
            if(in_array($key, $this->requiredParams) || in_array($key, $this->optionalParams)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}

/**
 * Class SendEmailRequest extends from RestRequest and is used for sending email request.
 */
class SendEmailRequest extends RestRequest
{
    public function __construct() {
        $this->requiredParams = array(
            'from',
            'to',
            'text'
        );
        $this->optionalParams = array(
            'subject',
            'cc',
            'bcc'
        );
    }

    /**
     * Parse the request and do validation
     * @param request $req request object
     * @return bool|string return error message if there is any, otherwise return true.
     */
    public function parseRequest($req) {

       $rs =  parent::parseRequest($req);
       if($rs !== true) {
           return $rs;
       }

        //check params is validate
        $validationError = array();

        //trim email address
        $this->params['to'] = trim($this->params['to']," \t\n\r\0\x0B,");
        $this->params['from'] = trim($this->params['from'], " \t\n\r\0\x0B,");
        if(isset($this->params['cc'])) {
            $this->params['cc'] = trim($this->params['cc'], " \t\n\r\0\x0B," );
        }
        if(isset($this->params['bcc'])) {
            $this->params['bcc'] = trim($this->params['bcc'], " \t\n\r\0\x0B,");
        }

        //validate 'to' is an email
        if(!$this->validateEmailAddressList($this->params['from'])) {
            $validationError[] = 'from email is not validate';
        }
        if(!$this->validateEmailAddressList($this->params['to'])) {
            $validationError[] = 'to email is not validate';
        }
        if(!$this->validateEmailAddressList($this->params['cc'])) {
            $validationError[] = 'cc email is not validate';
        }
        if( !$this->validateEmailAddressList($this->params['bcc'])) {
            $validationError[] = 'bcc email is not validate';
        }

        if(trim($this->params['text']) === '') {
            $validationError [] = 'text is empty';
        }

        if(!empty($validationError)) {
            return implode(',', $validationError);
        }

        return true;
    }

    /**
     * Validate email address
     * @param $list email address list
     * @return bool whether is validated or not.
     */
    protected function validateEmailAddressList($list) {
        $listArray = explode(',', $list);
        foreach($listArray as $email) {
            $trimEmail = trim($email);
            if(!empty($trimEmail) && !filter_var($trimEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        return true;
    }
}