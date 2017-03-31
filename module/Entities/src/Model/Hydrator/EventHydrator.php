<?php
namespace Entities\Model\Hydrator;

use Universe\Model\StarRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Entities\Model\UserRepository;
use Entities\Model\Event;
use Entities\Model\BuildingTypeRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class EventHydrator implements HydratorInterface
{
    /**
     * @ PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @ SputnikRepository
     */
    private $sputnikRepository;
    
    /**
     * @ StarRepository
     */
    private $starRepository;
    
    /**
     * @ UserRepository
     */
    private $userRepository;
    
    /**
     * @ BuildingTypeRepository
     */
    private $buildingTypeRepository;
    
    
    public function __construct(
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        StarRepository $starRepository,
        UserRepository $userRepository,
        BuildingTypeRepository $buildingTypeRepository)
    {
        $this->planetRepository = $planetRepository;
        $this->sputnikRepository = $sputnikRepository;
        $this->starRepository = $starRepository;
        $this->userRepository = $userRepository;
        $this->buildingTypeRepository = $buildingTypeRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  Employee $object
     *
     * @return Event []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Event) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP Event object)',
                __METHOD__
            ));
        }
        $event = new Event(null,null,null,null,null,null,null,null,null,null,null,null);
        $event->exchangeArray($data);
        $user_id = $event->getUser();
        $star_id = $event->getTargetStar();
        $planet_id = $event->getTargetPlanet();
        $sputnik_id = $event->getTargetSputnik();
        $target_building_type_id = $event->getTargetBuildingType();
        try {
            $user = $this->userRepository->findEntity($user_id);
        }
        catch (\Exception $e) {
            $user = null;
        }
        try {
            $star = $this->starRepository->findEntity($star_id);
        }
        catch (\Exception $e) {
            $star = null;
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
            $targetBuildingType = $this->buildingTypeRepository->findEntity($target_building_type_id);
        }
        catch (\Exception $e) {
            $targetBuildingType = null;
        }
        $event->setUser($user);
        $event->setTargetStar($star);
        $event->setTargetPlanet($planet);
        $event->setTargetSputnik($sputnik);
        $event->setTargetBuildingType($targetBuildingType);
        return $event;
    }
    
    /**
     * @param Event $object
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
