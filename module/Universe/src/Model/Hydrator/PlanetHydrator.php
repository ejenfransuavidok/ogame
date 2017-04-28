<?php
namespace Universe\Model\Hydrator;

use Universe\Model\Planet;
use Universe\Model\Galaxy;
use Universe\Model\PlanetSystem;
use Universe\Model\PlanetType;
use Universe\Model\PlanetTypeRepository;
use Universe\Model\PlanetSystemRepository;
use Entities\Model\UserRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class PlanetHydrator implements HydratorInterface
{
    
    /**
     * 
     * @PlanetSystemRepository
     * 
     */
    private $planetSystemRepository;
    
    /**
     * 
     * @PlanetTypeRepository
     * 
     */
    private $planetTypeRepository;
    
    /**
     * @ UserRepository
     */
    private $userRepository;
    
    
    public function __construct(
        PlanetSystemRepository $planetSystemRepository, 
        PlanetTypeRepository $planetTypeRepository,
        UserRepository $userRepository)
    {
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetTypeRepository = $planetTypeRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Employee $object
     *
     * @return PlanetSystem []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Planet) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Planet object)',
                __METHOD__
            ));
        }
        $planet = new Planet(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        $planet->exchangeArray($data);
        $planet_system_id = $planet->getCelestialParent();
        $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
        $planet_type_id = $planet->getType();
        $planet_type = $this->planetTypeRepository->findEntity($planet_type_id);
        $planet->setType($planet_type);
        $planet->setCelestialParent($planet_system);
        $owner_id = $planet->getOwner();
        try {
            $owner = $this->userRepository->findEntity($owner_id);
        }
        catch (\Exception $e) {
            $owner = null;
        }
        $planet->setOwner($owner);
        return $planet;
    }
    
    /**
     * @param PlanetSystem $object
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
