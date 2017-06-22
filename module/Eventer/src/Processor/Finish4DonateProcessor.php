<?php

namespace Eventer\Processor;

use Zend\View\Model\ViewModel;
use Entities\Model\Event;
use Eventer\Processor\ResourcesCalculator;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Model\EventCommand;
use Entities\Model\BuildingType;
use Universe\Model\PlanetRepository;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikRepository;
use Universe\Model\SputnikCommand;

class FailEventCelestial extends \Exception
{
}

class Finish4DonateProcessor
{
    
    static public function execute
    (
        Event &$event,
        EventCommand $eventCommand,
        SettingsRepositoryInterface $settingsRepository, 
        PlanetCommand $planetCommand, 
        SputnikCommand $sputnikCommand,
        ViewModel &$view
    )
    {
        $event_begin    = $event->getEventBegin();
        $event_end      = $event->getEventEnd();
        $now            = time();
        $buildingType   = $event->getTargetBuildingType();
        $level          = $event->getTargetLevel();
        $celestial      = $event->getTargetPlanet() ? $event->getTargetPlanet() : $event->getTargetSputnik();
        if(! $celestial){
            throw new FailEventCelestial('planet and sputnik are NULL!');
        }
        /**
         * @ актуально ?
         */
        if($now < $event_end){
            $total_t = $event_end - $event_begin;
            $delta_t = $event_end - $now;
            $portion = floatval(floatval($delta_t) / floatval($total_t));
            $total_d = floatval(self::getTotalDonateNeedle($settingsRepository, $buildingType, $level, $portion));
            $have_donate = floatval($celestial->getAnti());
            if($have_donate >= $total_d){
                /**
                 * @ списываем донат, изменяем событие, возвращаем успех
                 */
                $celestial->setAnti($have_donate - $total_d);
                $commander = $event->getTargetPlanet() ? $planetCommand : $sputnikCommand;
                $commander->updateEntity($celestial);
                $event->setEventEnd($now);
                $event = $eventCommand->updateEntity($event);
                $view->setVariable('data', array('result' => 'YES', 'auth' => 'YES', 'message' => 'Строительство данного объекта завершено за донат'));
                return $view;
            }
            else{
                $view->setVariable('data', array(
                    'result' => 'NO', 
                    'auth' => 'YES', 
                    'message' => 'Недостаточно доната на счету ! Имеется: ' . $have_donate . ', нужно: ' . $total_d));
                return $view;
            }
        }
        $view->setVariable('data', array('result' => 'NO', 'auth' => 'YES', 'message' => 'Здание уже построено !'));
        return $view;
    }
    
    static public function getTotalDonateNeedle(SettingsRepositoryInterface $settingsRepository, BuildingType $buildingType, $level, $portion)
    {
        list($electricity, $metall, $heavygas, $ore, $hydro, $titan, $darkmatter, $redmatter, $anti) =
            ResourcesCalculator::getConsumedResources($buildingType, $level);
        $metall     = floatval($metall)        * floatval($portion) * 
            floatval($settingsRepository->findSettingByKey ('DONATE_2_METALL_4_FINISHING_BUILDING')->getText());
        $heavygas   = floatval($heavygas)      * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_HEAVYGAS_4_FINISHING_BUILDING')->getText());
        $ore        = floatval($ore)           * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_ORE_4_FINISHING_BUILDING')->getText());
        $hydro      = floatval($hydro)         * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_HYDROGENIUM_4_FINISHING_BUILDING')->getText());
        $titan      = floatval($titan)         * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_TITAN_4_FINISHING_BUILDING')->getText());
        $darkmatter = floatval($darkmatter)    * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_DARKMATTER_4_FINISHING_BUILDING')->getText());
        $redmatter  = floatval($redmatter)     * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_REDMATTER_4_FINISHING_BUILDING')->getText());
        $anti       = floatval($anti)          * floatval($portion) *
            floatval($settingsRepository->findSettingByKey ('DONATE_2_ANTI_4_FINISHING_BUILDING')->getText());
        $total_d    = floatval($metall) + floatval($heavygas) + floatval($ore) + floatval($hydro) + floatval($titan) + floatval($darkmatter)
            + floatval($redmatter) + floatval($anti);
        return floatval($total_d);
    }
    
}
