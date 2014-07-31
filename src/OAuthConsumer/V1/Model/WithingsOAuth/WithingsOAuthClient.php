<?php

namespace OAuthConsumer\V1\Model\WithingsOAuth;

use OAuthConsumer\V1\Model\OAuth1;
use OAuthConsumer\V1\Model\OAuthClientInterface;

use Zend\Http\Response;
use Zend\Db\Sql\Expression;
use ZF\ApiProblem\ApiProblem;

class WithingsOAuthClient implements OAuthClientInterface {

    public $request_token = '';
    public $request_token_secret = '';
    public $access_token = '';
    public $access_token_secret = '';

    // Used to make OAuth 1 calls
    private $oauth_core;
    private $request_token_table;
    private $oauth_provider = 'withings';

    public function __construct($config, WithingsOAuthTableGateway $request_token_table, WithingsAccessTokensTableGateway $access_table) {

        $this->oauth_core = new OAuth1($config);

        $this->request_token_table = $request_token_table;
        $this->access_token_table = $access_table;
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @param mixed $params
     * @return ApiProblem|mixed
     */
    public function fetch_authorization($id, $params)
    {
        if($id != $this->oauth_provider) {
            return new ApiProblem(403,"Unrecognized OAuth provider name. " . $id);
        }
        // retrieve the callback URL for the Authorization Step
        $oauth_callback_url = $params['oauth_callback'];


        // Attempt to retrieve a new set of RequestTokens from OAuth Provider
        $requestTokensArray = $this->getRequestToken($oauth_callback_url);

        // We need valid tokens in order to roll a proper Authorization URL
        if(empty($requestTokensArray) or $requestTokensArray instanceof ApiProblem) {
            if(empty($requestTokensArray)) {
                return new ApiProblem(403,"RequestTokens were not retrieved from provider " . $id);
            } else {
                return $requestTokensArray;
            }
        }

        // upload Request Token, Request Token secret, and OAuth Callback..

        $token_storage_array['oauth_callback'] = $oauth_callback_url;
        $token_storage_array['request_token'] = $requestTokensArray['oauth_token'];
        $token_storage_array['request_token_secret'] = $requestTokensArray['oauth_token_secret'];
        $token_storage_array['created_at'] = new Expression('NOW()');
        $this->request_token_table->insert($token_storage_array);

        // Generate an Authorization URL for the particular Request Token
        $authorizeURL = $this->getAuthorizationURL($requestTokensArray);

        $authorization_array['oauth_provider_id'] = $id;
        $authorization_array['authorize_url'] = $authorizeURL;

        return new ApiProblem('200',$authorization_array);
    }

    function fetch_access_token($oauth_provider_id, $params) {

        if($oauth_provider_id != $this->oauth_provider) {
            return new ApiProblem(403,"Unrecognized OAuth provider name. " . $oauth_provider_id);
        }

        // retrieve the callback URL for the Authorization Step
        $withingsId = $params['withingsid'];
        $authorized_request_token = $params['request_token'];
        $verifier = $params['verifier'];

        $rowset = $this->request_token_table->select(array('request_token' => $authorized_request_token));
        $result = $rowset->current();

        if(empty($result)) {
            return new ApiProblem('403', "stale or malformed request_token, please try a new token. ". $authorized_request_token);
        }

        // Form the parameters needed to request an access token from withings.
        $withings_params['userid'] = $withingsId;
        $withings_params['request_token'] =  $authorized_request_token;
        $withings_params['request_token_secret'] = $result->request_token_secret;

        $result = $this->getAccessToken($withings_params);

        return $result;
    }

    /**
     * Return a valid RequestToken, in order to generate a proper Authorize Apigility URL.
     *
     * @param $oauth_callback
     * @return string
     */
    function getRequestToken($oauth_callback) {

        // Generate a Request_Access OAuth Token with the oauth_callback url given
        $params = array (
                "oauth_callback" =>  $oauth_callback,
                "oauth_version" => "1.0",
                "oauth_nonce" => time(),
                "oauth_timestamp" => time(),
                "oauth_consumer_key" => $this->oauth_core->consumer_key,
                "oauth_signature_method" => "HMAC-SHA1"
            );


        $result = $this->oauth_core->getRequestToken("GET", $params);

        // Parse the results from our request for an OAuth Request Token
        if(!empty($result)) {
            $oauth_tokens = explode('&', $result);

            foreach($oauth_tokens as $token) {
                $temp = explode('=',$token);
                $oauth_token_array[$temp[0]] = $temp[1];
            }

            return $oauth_token_array;
        }

        return $result;
    }

    /**
     * Given the current parameters, generate a string that will authorize Apigility to request access from
     * an OAuth provider on behalf of some user.
     *
     * @param $request_token_array
     * @return string
     */
    public function getAuthorizationURL($request_token_array) {

        $params = array (
            "oauth_token" => $request_token_array['oauth_token'],
            "oauth_version" => "1.0",
            "oauth_nonce" => time(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->oauth_core->consumer_key,
            "oauth_signature_method" => "HMAC-SHA1"
        );

        return $this->oauth_core->getAuthorizationURL("GET",$params);
    }

    /**
     * Formulate the parameters needed, and execute a GET to retrieve an access token
     *
     * @param $access_token_params
     * @return ApiProblem
     */
    public function getAccessToken($access_token_params) {

        if(empty($access_token_params)) {
            return new ApiProblem('500', "Empty parameters");
        }

        $request_token_secret = $access_token_params['request_token_secret'];

        $params = array(
            "oauth_token" => $access_token_params['request_token'],
            "oauth_version" => "1.0",
            "oauth_nonce" => time(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->oauth_core->consumer_key,
            "oauth_signature_method" => "HMAC-SHA1",
            "userid" => $access_token_params["userid"],
        );

        $result = $this->oauth_core->getAccessToken("GET", $params, $request_token_secret);
        //TODO: Store into access_token table

        return $result;

    }

}