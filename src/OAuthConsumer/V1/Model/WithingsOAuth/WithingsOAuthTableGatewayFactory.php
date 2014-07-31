<?php

namespace OAuthConsumer\V1\Model\WithingsOAuth;

use DomainException;

use Zend\ServiceManager\ServiceManager;

class WithingsOAuthTableGatewayFactory
{
    public function __invoke(ServiceManager $services)
    {
        $db    = 'OAuthConsumerDB';
        $table = 'withings_oauth';

        if ($services->has('config')) {
            $config = $services->get('config');
            switch (isset($config['OAuthConsumerDB'])) {
                case true:
                    $config = $config['OAuthConsumerDB'];

                    if (array_key_exists('db', $config) && !empty($config['db'])) {
                        $db = $config['db'];
                    }

                    if (array_key_exists('table', $config) && !empty($config['table'])) {
                        $table = $config['table'];
                    }
                    break;
                case false:
                default:
                    break;
            }
        }

        if (!$services->has($db)) {
            throw new DomainException(sprintf(
                'Unable to create' . get_class($this) . ' due to missing "%s" service',
                $db
            ));
        }

        return new WithingsOAuthTableGateway($table, $services->get($db));
    }
}

