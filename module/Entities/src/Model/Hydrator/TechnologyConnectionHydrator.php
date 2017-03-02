<?php
namespace Entities\Model\Hydrator;

use Entities\Model\Technology;
use Entities\Model\TechnologyConnection;
use Entities\Model\TechnologyRepository;
use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\HydratorInterface;

class TechnologyConnectionHydrator implements HydratorInterface
{
    /**
     * 
     * @TechnologyRepository
     * 
     */
    private $technologyRepository;
    
    public function __construct(TechnologyRepository $technologyRepository)
    {
        $this->technologyRepository = $technologyRepository;
    }
    
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  TechnologyConnection $object
     *
     * @return TechnologyConnection []
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof TechnologyConnection) {
            throw new \BadMethodCallException(sprintf(
                '%s expects the provided $object:'.get_class($object).' to be a PHP TechnologyConnection object)',
                __METHOD__
            ));
        }
        
        $technologyConnection = new TechnologyConnection(null,null,null,null);
        $technologyConnection->exchangeArray($data);
        $tech_1 = $technologyConnection->getTech_1();
        $tech_2 = $technologyConnection->getTech_2();
        $tech_1 = $this->technologyRepository->findEntity($tech_1);
        $tech_2 = $this->technologyRepository->findEntity($tech_2);
        $technologyConnection->setTech_1($tech_1);
        $technologyConnection->setTech_1($tech_2);
        return $technologyConnection;
    }
    
    /**
     * @param TechnologyConnection $object
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
