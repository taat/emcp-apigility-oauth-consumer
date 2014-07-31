<?php

namespace OAuthConsumer\V1\Model\WithingsOAuth;

use Zend\ServiceManager\ServiceManager;
use DomainException;

class WithingsOAuthClientFactory
{
    public function __invoke(ServiceManager $services)
    {

        //OAuth Authorizations Table
        $auth_table_dependency = 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthTableGateway';
        if(!$services->has($auth_table_dependency)) {
            throw new \DomainException("Cannot Create " . get_class($this) . '; Missing dependency ' . $auth_table_dependency);
        }

        //OAuth Access Tokens Table
        $access_token_table_dependency = 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsAccessTokensTableGateway';
        if(!$services->has($access_token_table_dependency)) {
            throw new \DomainException("Cannot Create " . get_class($this) . '; Missing dependency ' . $access_token_table_dependency);
        }

        // Find the Withings OAuth Key settings
        if ($services->has('config')) {
            $config = $services->get('config');

            switch(isset($config['oauth-client'])) {
                case true:
                    $config = $config['oauth-client'];
                    if (array_key_exists('withings', $config) && !empty($config['withings'])) {
                        $withingsConfig = $config['withings'];
                    }
                    break;
                case false:
                default:
                    break;
            }
        }

        if(empty($withingsConfig)){
            throw new \DomainException("Cannot Create " . get_class($this) . '; Missing corresponding config entry under oauth-client');
        }

        return new WithingsOAuthClient($withingsConfig, $services->get($auth_table_dependency), $services->get($access_token_table_dependency));
    }

}
