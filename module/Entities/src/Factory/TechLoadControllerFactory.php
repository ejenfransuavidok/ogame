<?php

namespace Entities\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Entities\Model\TechnologyRepository;
use Entities\Controller\TechLoadController;
use Entities\Model\TechnologyCommand;
use Entities\Model\TechnologyConnectionCommand;

class TechLoadControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TechLoadController(
            $container->get(TechnologyRepository::class),
            $container->get(TechnologyCommand::class),
            $container->get(TechnologyConnectionCommand::class)
        );
    }
}
