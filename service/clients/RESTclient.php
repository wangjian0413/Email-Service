<?php

/**
 * A client to wrap up the curl calls, to make it easier to send requests to
 * REST-style endpoints.
 */
class RESTClient
{
    protected $urlList;
    protected $maskedUrl;

	protected $method;

	protected $responseBody;
	protected $responseCode;
	protected $errorMessage;

	protected $curlOpts = array();
	protected $headers = array();

    /** Construct the REST client based on url(s). If $urlList is an array, connection timeouts will attempt
     * fail over connections to other URL in this array.
     * @param $urlList array URL(s)
     * @param $method HTTP Request method
     */
    public function __construct($urlList, $method)
	{
		if(!is_array($urlList)) {
			$urlList = array($urlList);
		}
		$this->reset();
		$this->setUrl($urlList, $method);
    }

    /** Sets url and http request methods.
     * @param $urlList array URL(s)
     * @param $method HTTP Request method
     */
    public function setUrl($urlList, $method)
	{
		$this->urlList = $urlList;
		$this->method = strtoupper($method);
	}

    /**
     * Resets the client's member so that is can be re-used.
     */
    public function reset()
	{
		$this->curlOpts = array();
		$this->headers = array();

		$this->responseBody = null;
		$this->responseCode = null;
		$this->errorMessage = null;

		$this->setCurlOption(CURLOPT_CONNECTTIMEOUT, 30);
		$this->setCurlOption(CURLOPT_TIMEOUT, 120);

		$this->setCurlOption(CURLOPT_VERBOSE, false);
		$this->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
		$this->setCurlOption(CURLOPT_SSL_VERIFYHOST, false);
		$this->setCurlOption(CURLOPT_RETURNTRANSFER, true);
	}

	public function setCurlOption($option, $value)
	{
		$this->curlOpts[$option] = $value;
	}

	public function setHeader($name, $value)
	{
		$this->headers[$name] = $value;
	}

    /** Send REST request to specific URL
     * @param $getParams name/value pairs to include in the URL
     * @param $postBody name/value pair to set in POST/PUT/DELETE methods
     * @return bool true if sending request is successful and return code 200
     */
    public function sendRequest($getParams, $postBody)
    {
        if (count($this->urlList) > 1) {
            $this->setCurlOption(CURLOPT_CONNECTTIMEOUT, 12);
        }

        foreach ($this->urlList as $url) {
            $parts = explode('@', $url);
            if (count($parts) > 1) {
                $this->maskedUrl = $parts[1];
            } else {
                $this->maskedUrl = $url;
            }

            if (!empty($getParams)) {
                if (is_array($getParams)) {
                    $url .= '?' . self::CreateUrlParamString($getParams);
                } else {
                    $url .= '?' . strval($getParams);
                }
            }

            try {
                $ch = curl_init($url);

                foreach ($this->curlOpts as $option => $value) {
                    curl_setopt($ch, $option, $value);
                }

                // Set the headers if specified
                if (!empty($this->headers)) {
                    $headerValues = array();
                    foreach ($this->headers as $name => $value) {
                        $headerValues[] = $name . ': ' . $value;
                    }
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerValues);
                }

                // Set the POST body, if specified
                if ($this->method == 'POST') {
                    curl_setopt($ch, CURLOPT_POST, true);

                    if (!empty($postBody)) {
                        if (is_array($postBody)) {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, self::CreateUrlParamString($postBody));
                        } else {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
                        }
                    } else {
                        // Need to always set this if the method is POST
                        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
                    }
                }

                // Make the call
                $this->responseBody = curl_exec($ch);
            } catch (Exception $ex) {
                $this->responseCode = -1;
                $this->errorMessage = $ex->getMessage();

                curl_close($ch);
                return false;
            }

            if (CURLE_COULDNT_CONNECT == curl_errno($ch)) {
                curl_close($ch);
                continue;
            }

            $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->errorMessage = curl_error($ch);

            curl_getinfo($ch, CURLINFO_CONNECT_TIME);

            if ($this->responseBody === false) {
                curl_close($ch);
                return false;
            }

            curl_close($ch);
            return true;
        }
    }

    public function getResponseCode()
	{
        return intval($this->responseCode);
    }

    public function getErrorMessage()
	{
        return $this->errorMessage;
    }

    public function getResponseBody()
	{
        return $this->responseBody;
    }

    public function getMaskedUrl()
    {
        return $this->maskedUrl;
    }

    /** Transfer a name/value pairs into a string including GET params that can be appended to URL.
     * @param array $params name/value pairs
     * @return string GET params
     */
    public static function CreateUrlParamString(array $params)
    {
        $nameValPairs	= array();
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $arrVal) {
                    $nameValPairs[]	= urlencode($key.'[]') . '=' . urlencode($arrVal);
                }
            } else if (is_null($val)) {
                $nameValPairs[]	= urlencode($key);
            } else {
                $nameValPairs[]	= urlencode($key) . '=' . urlencode($val);
            }
        }
        $paramString = implode('&', $nameValPairs);
        return $paramString;
    }
}
