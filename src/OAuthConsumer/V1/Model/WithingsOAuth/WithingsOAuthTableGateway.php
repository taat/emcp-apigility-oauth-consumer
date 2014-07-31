<?php
/**
 * Created by PhpStorm.
 * User: emcp
 * Date: 7/20/14
 * Time: 10:46 PM
 */
namespace OAuthConsumer\V1\Model\WithingsOAuth;

use OAuthConsumer\V1\Rest\Authorizations\AuthorizationsEntity;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway as ZFTableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;


/**
 * Custom TableGateway instance for OAuth related tables/data
 *
 * Creates a HydratingResultSet seeded with an ObjectProperty hydrator and Entity instance.
 */
class WithingsOAuthTableGateway extends ZFTableGateway
{
    public function __construct($table, AdapterInterface $adapter, $features = null)
    {

        $resultSet = new HydratingResultSet(new ObjectPropertyHydrator(), new AuthorizationsEntity());

        return parent::__construct($table, $adapter, $features, $resultSet);
    }
}
