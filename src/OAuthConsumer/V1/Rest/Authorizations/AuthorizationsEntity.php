<?php
namespace OAuthConsumer\V1\Rest\Authorizations;

class AuthorizationsEntity
{
    /**
     * @var string
     */
    public $oauth_provider_id;

    /**
     * @var string
     */
    public $authorization_url;

    /**
     * @var string
     */
    public $access_token_url;

    /**
     * @var string
     */
    public $request_token;

    /**
     * @var string
     */
    public $request_token_secret;
}
