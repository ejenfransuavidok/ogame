<?php

namespace App\Renderer;

use Zend\View\Model\ViewModel;
use Universe\Model\PlanetRepository;
use Universe\Classes\PlanetCapacity;
use Universe\Model\Planet;
use Entities\Model\User;
use Settings\Model\SettingsRepositoryInterface;

class HeaderRenderer
{
    static public function render
        (
            ViewModel                       &$template,
            PlanetRepository                $planetRepository,
            Planet                          $planet,
            PlanetCapacity                  $planetCapacity,
            User                            $user,
            SettingsRepositoryInterface     $settingsRepository
        )
    {
        $electricity = new ViewModel([]);
        $electricity
            ->setVariable('planet', $planet)
            ->setVariable('settings', $settingsRepository);
        $electricity->setTemplate('include/resources/electricity');
        
        $metall = new ViewModel([]);
        $metall
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $metall->setTemplate('include/resources/metall');
        
        $heavygas = new ViewModel([]);
        $heavygas
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $heavygas->setTemplate('include/resources/heavygas');
        
        $ore = new ViewModel([]);
        $ore
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $ore->setTemplate('include/resources/ore');
        
        $hydro = new ViewModel([]);
        $hydro
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $hydro->setTemplate('include/resources/hydro');
        
        $titan = new ViewModel([]);
        $titan
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $titan->setTemplate('include/resources/titan');
        
        $darkmatter = new ViewModel([]);
        $darkmatter
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $darkmatter->setTemplate('include/resources/darkmatter');
        
        $redmatter = new ViewModel([]);
        $redmatter
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $redmatter->setTemplate('include/resources/redmatter');
        
        $anti = new ViewModel([]);
        $anti
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('settings', $settingsRepository);
        $anti->setTemplate('include/resources/anti');
        
        $template
            ->setVariable('planets', $planetRepository->findBy('planets.owner = ' . $user->getId() . ' AND planets.id != ' . $planet->getId())->buffer())
            ->setVariable('planet', $planet)
            ->setVariable('capacity', $planetCapacity)
            ->setVariable('user', $user)
            ->setVariable('settings', $settingsRepository)
            ->addChild($electricity, 'electricity')
            ->addChild($metall, 'metall')
            ->addChild($heavygas, 'heavygas')
            ->addChild($ore, 'ore')
            ->addChild($hydro, 'hydro')
            ->addChild($titan, 'titan')
            ->addChild($darkmatter, 'darkmatter')
            ->addChild($redmatter, 'redmatter')
            ->addChild($anti, 'anti');
    }
}
