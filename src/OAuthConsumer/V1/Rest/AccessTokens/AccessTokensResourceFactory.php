<?php
namespace OAuthConsumer\V1\Rest\AccessTokens;

use DomainException;

class AccessTokensResourceFactory
{
    public function __invoke($services)
    {

        // retrieve the factory for  underlying OAuthClient
        $dependency = 'OAuthConsumer\\V1\\Model\\OAuthClient';

        if (!$services->has($dependency)) {
            throw new DomainException("Cannot create ". get_class($this) ."; missing $dependency dependency");
        }

        return new AccessTokensResource($services->get($dependency));
    }
}
