<?php
namespace Entities\Model\Hydrator;

use Universe\Model\StarRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Entities\Model\UserRepository;
use Entities\Model\EventRepository;
use Entities\Model\SpaceSheep;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class SpaceSheepHydrator implements HydratorInterface
{
    /**
     * 
     * @ GalaxyRepository
     * 
     */
    private $galaxyRepository;
    
    /**
     * 
     * @ PlanetSystemRepository
     * 
     */
    private $planetSystemRepository;
    
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
     * @ StarRepository
     * 
     */
    private $starRepository;
    
    /**
     * 
     * @ UserRepository
     * 
     */
    private $userRepository;
    
    /**
     * 
     * @ EventRepository
     * 
     */
    private $eventRepository;
    
    
    public function __construct(
        GalaxyRepository $galaxyRepository, 
        PlanetSystemRepository $planetSystemRepository,
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        StarRepository $starRepository,
        UserRepository $userRepository,
        EventRepository $eventRepository)
    {
        $this->galaxyRepository = $galaxyRepository;
        $this->planetSystemRepository = $planetSystemRepository;
        $this->planetRepository = $planetRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->starRepository = $starRepository;
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Employee $object
     *
     * @return SpaceSheep []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof SpaceSheep) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP SpaceSheep object)',
                __METHOD__
            ));
        }
        
        $spaceSheep = new SpaceSheep(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        $spaceSheep->exchangeArray($data);
        $galaxy_id = $spaceSheep->getGalaxy();
        $planet_system_id = $spaceSheep->getPlanetSystem();
        $planet_id = $spaceSheep->getPlanet();
        $sputnik_id = $spaceSheep->getSputnik();
        $star_id = $spaceSheep->getStar();
        $user_id = $spaceSheep->getOwner();
        $event_id = $spaceSheep->getEvent();
        try {
            $galaxy = $this->galaxyRepository->findEntity($galaxy_id);
        }
        catch (\Exception $e) {
            $galaxy = null;
        }
        try {
            $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
        }
        catch (\Exception $e) {
            $planet_system = null;
        }
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
            $star = $this->starRepository->findEntity($star_id);
        }
        catch (\Exception $e) {
            $star = null;
        }
        try {
            $user = $this->userRepository->findEntity($user_id);
        }
        catch (\Exception $e) {
            $user = null;
        }
        try {
            $event = $this->eventRepository->findEntity($event_id);
        }
        catch (\Exception $e) {
            $event = null;
        }
        $spaceSheep->setGalaxy($galaxy);
        $spaceSheep->setPlanetSystem($planet_system);
        $spaceSheep->setPlanet($planet);
        $spaceSheep->setSputnik($sputnik);
        $spaceSheep->setStar($star);
        $spaceSheep->setOwner($user);
        $spaceSheep->setEvent($event);
        return $spaceSheep;
    }
    
    /**
     * @param SpaceSheep $object
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
