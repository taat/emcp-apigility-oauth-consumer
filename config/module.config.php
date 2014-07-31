<?php
return array(
    'router' => array(
        'routes' => array(
            'o-auth-consumer.rest.authorizations' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/oauth_authorizations[/:oauth_provider_id]',
                    'defaults' => array(
                        'controller' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller',
                    ),
                ),
            ),
            'o-auth-consumer.rest.access-tokens' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/access-tokens[/:oauth_provider_id]',
                    'defaults' => array(
                        'controller' => 'OAuthConsumer\\V1\\Rest\\AccessTokens\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'o-auth-consumer.rest.authorizations',
            1 => 'o-auth-consumer.rest.access-tokens',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'OAuthConsumer\\V1\\Model\\OAuthClient' => 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthClient',
        ),
        'factories' => array(
            #Withings Specific
            'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthClient' => 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthClientFactory',
            'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthTableGateway' => 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsOAuthTableGatewayFactory',
            'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsAccessTokensTableGateway' => 'OAuthConsumer\\V1\\Model\\WithingsOAuth\\WithingsAccessTokensTableGatewayFactory',
            #AuthorizationService
            'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsResource' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsResourceFactory',
            #Access Token Service
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensResource' => 'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller' => array(
            'listener' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsResource',
            'route_name' => 'o-auth-consumer.rest.authorizations',
            'route_identifier_name' => 'oauth_provider_id',
            'collection_name' => 'authorizations',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsEntity',
            'collection_class' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsCollection',
            'service_name' => 'Authorizations',
        ),
        'OAuthConsumer\\V1\\Rest\\AccessTokens\\Controller' => array(
            'listener' => 'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensResource',
            'route_name' => 'o-auth-consumer.rest.access-tokens',
            'route_identifier_name' => 'oauth_provider_id',
            'collection_name' => 'access_tokens',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensEntity',
            'collection_class' => 'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensCollection',
            'service_name' => 'AccessTokens',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller' => 'HalJson',
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller' => array(
                0 => 'application/vnd.o-auth-consumer.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\Controller' => array(
                0 => 'application/vnd.o-auth-consumer.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller' => array(
                0 => 'application/vnd.o-auth-consumer.v1+json',
                1 => 'application/json',
            ),
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\Controller' => array(
                0 => 'application/vnd.o-auth-consumer.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsEntity' => array(
                'entity_identifier_name' => 'oauth_provider_id',
                'route_name' => 'o-auth-consumer.rest.authorizations',
                'route_identifier_name' => 'oauth_provider_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ObjectProperty',
            ),
            'OAuthConsumer\\V1\\Rest\\Authorizations\\AuthorizationsCollection' => array(
                'entity_identifier_name' => 'oauth_provider_id',
                'route_name' => 'o-auth-consumer.rest.authorizations',
                'route_identifier_name' => 'oauth_provider_id',
                'is_collection' => true,
            ),
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'o-auth-consumer.rest.access-tokens',
                'route_identifier_name' => 'oauth_provider_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'OAuthConsumer\\V1\\Rest\\AccessTokens\\AccessTokensCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'o-auth-consumer.rest.access-tokens',
                'route_identifier_name' => 'oauth_provider_id',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-content-validation' => array(
        'OAuthConsumer\\V1\\Rest\\Authorizations\\Controller' => array(
            'input_filter' => 'OAuthConsumer\\V1\\Rest\\Authorizations\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'OAuthConsumer\\V1\\Rest\\Authorizations\\Validator' => array(
            0 => array(
                'name' => 'oauth_provider_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            1 => array(
                'name' => 'oauth_callback',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
    ),
);
