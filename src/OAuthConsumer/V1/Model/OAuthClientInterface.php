<?php
/**
 * This custom interface is meant to wrap over the services of the OAuthConsumer Resource,
 * and anything underlying the Resource must implement that interface.
 *
 * Currently Valid HTTP Methods
 *
 * OPTIONS
 * GET
 * HEAD
 * POST
 * PUT
 * DELETE
 * TRACE
 * CONNECT
 *
 */

namespace OAuthConsumer\V1\Model;


interface OAuthClientInterface
{

    public function fetch_authorization($id, $params);

    public function fetch_access_token($oauth_provider_id, $params);

    public function getRequestToken($params);

    public function getAuthorizationURL($params);

    public function getAccessToken($params);

}
