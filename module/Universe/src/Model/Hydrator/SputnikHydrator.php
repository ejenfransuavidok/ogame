<?php
namespace Universe\Model\Hydrator;

use Universe\Model\Sputnik;
use Universe\Model\Galaxy;
use Universe\Model\SputnikSystem;
use Universe\Model\SputnikRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class SputnikHydrator implements HydratorInterface
{
    /**
     * 
     * @PlanetSystemRepository
     * 
     */
    private $planetSystemRepository;
    
    /**
     * 
     * @PlanetRepository
     * 
     */
    private $planetRepository;
    
    public function __construct(PlanetSystemRepository $planetSystemRepository, PlanetRepository $planetRepository)
    {
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetRepository = $planetRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  SPutnik $object
     *
     * @return Sputnik []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Sputnik) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Star object)',
                __METHOD__
            ));
        }
        $sputnik = new Sputnik('','','','','','','');
        $sputnik->exchangeArray($data);
        $parent_planet_id = $sputnik->getParentPlanet();
        $parent_planet = $this->planetRepository->findEntity($parent_planet_id);
        $sputnik->setParentPlanet($parent_planet);
        $celestial_parent_id = $sputnik->getCelestialParent();
        $celestial_parent = $this->planetSystemRepository->findEntity($celestial_parent_id);
        $sputnik->setCelestialParent($celestial_parent);
        return $sputnik;
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
