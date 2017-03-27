<?php
namespace Entities\Model\Hydrator;

use Entities\Model\User;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class UserHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  User $object
     *
     * @return User []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof User) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Star object)',
                __METHOD__
            ));
        }
        $user = new User(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        $user->exchangeArray($data);
        return $user;
    }
    
    /**
     * @param Sputnik $object
     *
     * @return mixed
     */
    public function extract($object)
    {
        if (!is_callable([$object, 'getArrayCopy'])) {
            throw new BadMethodCallException(
                sprintf('%s expects the provided object to implement getArrayCopy()', __METHOD__)
            );
        }
        return $object->getArrayCopy();
    }
}
