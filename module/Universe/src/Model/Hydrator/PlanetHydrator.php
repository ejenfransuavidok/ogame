<?php
namespace Universe\Model\Hydrator;

use Universe\Model\Planet;
use Universe\Model\Galaxy;
use Universe\Model\PlanetSystem;
use Universe\Model\PlanetType;
use Universe\Model\PlanetTypeRepository;
use Universe\Model\PlanetSystemRepository;
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
    
    public function __construct(PlanetSystemRepository $planetSystemRepository)
    {
        $this->planetSystemRepository = $planetSystemRepository;
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
        $planet = new Planet('','','','','','','');
        $planet->exchangeArray($data);
        $planet_system_id = $planet->getCelestialParent();
        $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
        $planet->setCelestialParent($planet_system);
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
