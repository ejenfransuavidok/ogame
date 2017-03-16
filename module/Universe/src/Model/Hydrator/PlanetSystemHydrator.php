<?php
namespace Universe\Model\Hydrator;

use Universe\Model\Star;
use Universe\Model\Galaxy;
use Universe\Model\PlanetSystem;
use Universe\Model\StarTypeRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class PlanetSystemHydrator implements HydratorInterface
{
    /**
     * 
     * @StarTypeRepository
     * 
     */
    private $starTypeRepository;
    
    public function __construct(StarTypeRepository $starTypeRepository)
    {
        $this->starTypeRepository = $starTypeRepository;
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
        if (!$object instanceof PlanetSystem) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP PlanetSystem object)',
                __METHOD__
            ));
        }
        $planet_system = new PlanetSystem(null,null,null,null,null,null,null);
        $planet_system->exchangeArray($data);
        $star = new Star(null,null,null,null,null,null);
        $star->exchangeArray($data, 'stars.');
        $star->setCelestialParent($planet_system);
        $star_type = $this->starTypeRepository->findEntity($star->getStarType());
        $star->setStarType($star_type);
        $planet_system->setStar($star);
        $galaxy = new Galaxy(null,null,null,null);
        $galaxy->exchangeArray($data, 'galaxies.');
        $planet_system->setGalaxy($galaxy);
        return $planet_system;
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
