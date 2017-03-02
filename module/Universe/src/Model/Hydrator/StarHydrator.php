<?php
namespace Universe\Model\Hydrator;

use Universe\Model\Star;
use Universe\Model\Galaxy;
use Universe\Model\PlanetSystem;
use Universe\Model\StarType;
use Universe\Model\StarTypeRepository;
use Universe\Model\PlanetSystemRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class StarHydrator implements HydratorInterface
{
    /**
     * 
     * @StarTypeRepository
     * 
     */
    private $starTypeRepository;
    
    /**
     * 
     * @PlanetSystemRepository
     * 
     */
    private $planetSystemRepository;
    
    public function __construct(StarTypeRepository $starTypeRepository, PlanetSystemRepository $planetSystemRepository)
    {
        $this->starTypeRepository = $starTypeRepository;
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
        if (!$object instanceof Star) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Star object)',
                __METHOD__
            ));
        }
        $star = new Star('','','','','','');
        $star->exchangeArray($data);
        $planet_system_id = $star->getCelestialParent();
        $star_type_id = $star->getStarType();
        $star_type = $this->starTypeRepository->findEntity($star_type_id);
        $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
        $star->setStarType($star_type);
        $star->setCelestialParent($planet_system);
        return $star;
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
