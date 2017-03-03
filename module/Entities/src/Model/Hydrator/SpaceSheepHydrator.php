<?php
namespace Universe\Model\Hydrator;

use Universe\Model\StarReposytory;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Entities\Model\UserRepository;
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
    
    public function __construct(
        GalaxyRepository $galaxyRepository, 
        PlanetSystemRepository $planetSystemRepository, 
        SputnikRepository $sputnikRepository,
        StarRepository $starRepository,
        UserRepository $userRepository)
    {
        $this->galaxyRepository = $galaxyRepository;
        $this->planetSystemRepository = $planetSystemRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->starRepository = $starRepository;
        $this->userRepository = $userRepository;
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
                '%s expects the provided $object:'.get_class($object).' to be a PHP Star object)',
                __METHOD__
            ));
        }
        
        $spaceSheep = new SpaceSheep(null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
        $spaceSheep->exchangeArray($data);
        $galaxy_id = $spaceSheep->getGalaxy();
        $planet_system_id = $spaceSheep->getPlanetSystem();
        $planet_id = $spaceSheep->getPlanet();
        $sputnik_id = $spaceSheep->getSputnik();
        $star_id = $spaceSheep->getStar();
        $user_id = $spaceSheep->getOwner();
        $galaxy = $this->galaxyRepository->findEntity($galaxy_id);
        $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
        $planet = $this->planetRepository->findEntity($planet_id);
        $star = $this->starRepository->findEntity($star_id);
        $user = $this->userRepository->findEntity($user_id);
        $spaceSheep->setGalaxy($galaxy);
        $spaceSheep->setPlanetSystem($planet_system);
        $spaceSheep->setPlanet($planet);
        $spaceSheep->setStar($star);
        $spaceSheep->setUser($user);
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
