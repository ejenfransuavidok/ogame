<?php
namespace Entities\Model\Hydrator;

use Entities\Model\User;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Universe\Model\StarRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class UserHydrator implements HydratorInterface
{
    /**
     * @ var GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @ var PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @ var PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ var SputnikRepository
     */
    private $sputnikRepository;
    
    /**
     * @ var StarRepository
     */
    private $starRepository;
    
    public function __construct(
        GalaxyRepository $galaxyRepository, PlanetSystemRepository $planetSystemRepository,
        PlanetRepository $planetRepository, SputnikRepository $sputnikRepository,
        StarRepository $starRepository)
    {
        $this->galaxyRepository         = $galaxyRepository;
        $this->planetSystemRepository   = $planetSystemRepository;
        $this->planetRepository         = $planetRepository;
        $this->sputnikRepository        = $sputnikRepository;
        $this->starRepository           = $starRepository;
    }
    
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
        $user = User(null,null,null,null,null,null,null,null,null,null,null);
        $user->exchangeArray($data);
        $galaxy_id = $user->getGalaxy();
        try {
            $galaxy = $this->galaxyRepository->findEntity($galaxy_id);
            $user->setGalaxy($galaxy);
        }
        catch(\Exception $e) {}
        $planet_system_id = $user->getPlanetSystem();
        try {
            $planet_system = $this->planetSystemRepository->findEntity($planet_system_id);
            $user->setPlanetSystem($planet_system);
        }
        catch(\Exception $e) {}
        $planet_id = $user->getPlanet();
        try {
            $planet = $this->planetRepository->findEntity($planet_id);
            $user->setPlanet($planet);
        }
        catch(\Exception $e) {}
        $sputnik_id = $user->getSputnik();
        try {
            $sputnik = $this->sputnikRepository->findEntity($sputnik_id);
            $user->setSputnik($sputnik);
        }
        catch(\Exception $e) {}
        $star_id = $user->getStar();
        try {
            $star = $this->starRepository->findEntity($star_id);
            $user->setStar($star);
        }
        catch(\Exception $e) {}
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
