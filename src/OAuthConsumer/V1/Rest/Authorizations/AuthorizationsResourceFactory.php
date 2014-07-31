<?php
namespace OAuthConsumer\V1\Rest\Authorizations;

use DomainException;
use Zend\ServiceManager\ServiceManager;

class AuthorizationsResourceFactory
{
    public function __invoke(ServiceManager $services)
    {

        // retrieve the factory for  the underlying OAuthClient
        $dependency = 'OAuthConsumer\\V1\\Model\\OAuthClient';

        if (!$services->has($dependency)) {
            throw new DomainException("Cannot create ". get_class($this) ."; missing $dependency dependency");
        }

        return new AuthorizationsResource($services->get($dependency));
    }
}
