<?php
namespace Entities\Model\Hydrator;

use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Entities\Model\UserRepository;
use Entities\Model\Building;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class BuildingHydrator implements HydratorInterface
{
    /**
     * 
     * @ PlanetRepository
     * 
     */
    private $planetRepository;
    
    /**
     * 
     * @ SputnikRepository
     * 
     */
    private $sputnikRepository;
    
    /**
     * 
     * @ UserRepository
     * 
     */
    private $userRepository;
    
    public function __construct(
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        UserRepository $userRepository)
    {
        $this->planetRepository = $planetRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Employee $object
     *
     * @return Building []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Building) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Building object)',
                __METHOD__
            ));
        }
        
        $building = new Building(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        $building->exchangeArray($data);
        $planet_id = $building->getPlanet();
        $sputnik_id = $building->getSputnik();
        $user_id = $building->getOwner();
        try {
            $planet = $this->planetRepository->findEntity($planet_id);
        }
        catch (\Exception $e) {
            $planet = null;
        }
        try {
            $sputnik = $this->sputnikRepository->findEntity($sputnik_id);
        }
        catch (\Exception $e) {
            $sputnik = null;
        }
        try {
            $user = $this->userRepository->findEntity($user_id);
        }
        catch (\Exception $e) {
            $user = null;
        }
        $building->setPlanet($planet);
        $building->setSputnik($sputnik);
        $building->setOwner($user);
        return $building;
    }
    
    /**
     * @param Building $object
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
