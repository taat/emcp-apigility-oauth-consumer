<?php

namespace OAuthConsumer\V1\Model;

use ZF\ApiProblem\ApiProblem;

/**
 *
 * This class acts as the basis for OAuth 1 based API calls.  It will get RequestTokens, generate Authorization URLs,
 * and save entries to MySQL if needed.
 *
 * References : https://dev.twitter.com/docs/auth/creating-signature
 *
 * Class OAuth1
 * @package OAuthConsumer\V1\Model
 */
class OAuth1 {

    public $consumer_key = '';
    public $consumer_secret = '';
    public $request_token_url = '';
    public $access_token_url = '';

    function __construct($config)
    {
        $this->consumer_key = $config['consumer_key'];
        $this->consumer_secret = $config['consumer_secret'];
        $this->request_token_url = $config['request_token_url'];
        $this->authorize_url = $config['authorize_url'];
        $this->access_token_url = $config['access_token_url'];
    }

    /**
     * Return a valid RequestToken, in order to generate a proper Authorize Apigility URL.
     *
     * @param $http_method
     * @param $params
     * @return string
     */
    function getRequestToken($http_method, $params) {
        return $this->_http($this->OAuth1($http_method, $this->request_token_url, $params));
    }

    /**
     * Generate an authorization URL, given the parameters passed in.
     * @param http_method
     * @param $params
     * @return string
     */
    function getAuthorizationURL($http_method, $params) {
        return $this->OAuth1($http_method, $this->authorize_url, $params);
    }

    /**
     * Generate an access token, given the parameters passed in.
     * @param http_method
     * @param $params
     * @param $token_secret
     * @return string
     */
    function getAccessToken($http_method, $params, $token_secret) {
        return $this->_http($this->OAuth1($http_method, $this->access_token_url, $params, $token_secret));
    }

    private function OAuth1($http_method, $uri, $params, $token_secret = null ) {

        uksort($params, 'strcmp');

        // convert params to string
        foreach ($params as $k => $v) {
            $pairs[] = $this->_urlencode_rfc3986($k).'='.$this->_urlencode_rfc3986($v);
        }
        $concatenatedParams = implode('&', $pairs);

        // form base string (first key)
        $baseString= $http_method."&".$this->_urlencode_rfc3986($uri)."&".$this->_urlencode_rfc3986($concatenatedParams);
        // form secret (second key)
        if($token_secret == null) {
            $secret = $this->_urlencode_rfc3986($this->consumer_secret)."&";
        } else {
            // Only used if there is a token secret present.. https://dev.twitter.com/docs/auth/creating-signature
            $secret = $this->_urlencode_rfc3986($this->consumer_secret)."&".$this->_urlencode_rfc3986($token_secret);
        }
        // make signature and append to params
        $params['oauth_signature'] = $this->_urlencode_rfc3986(base64_encode(hash_hmac('sha1', $baseString, $secret, TRUE)));

        // BUILD URL
        // Resort
        uksort($params, 'strcmp');
        // convert params to string
        foreach ($params as $k => $v) {
            $urlPairs[] = $k."=".$v;
        }
        $concatenatedUrlParams = implode('&', $urlPairs);
        // form url
        $url = $uri."?".$concatenatedUrlParams;


       return $url;
    }

    private function _http($url)
    {
        $curl_config = array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array (CURLOPT_URL => $url,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
            ),
        );

        $client = new \Zend\Http\Client($url, $curl_config);

        $response = $client->send();

        if(!$response->isOk()) {
            return new ApiProblem('500', "Error performing request to OAuth Provider. " . $response->toString());
        } else {
            $result = $response->getBody();
            return $result;
        }
    }

    public function _urlencode_rfc3986($input)
    {

        if (is_array($input)) {
            return array_map(array('OAuthConsumer\\V1\\Model\\OAuth1', '_urlencode_rfc3986'), $input);
        } else if (is_scalar($input)) {
            return str_replace('+',' ',str_replace('%7E', '~', rawurlencode($input)));
        } else {
            return '';
        }
    }

}