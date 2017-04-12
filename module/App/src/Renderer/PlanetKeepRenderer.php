<?php

namespace App\Renderer;

use Zend\View\Model\ViewModel;
use Entities\Model\EventRepository;
use App\Controller\AuthController;

class PlanetKeepRenderer
{
    
    /**
     * @ EventRepository
     */
    private $eventRepository;
    
    /**
     * @ AuthController
     */
    private $authController;
    
    public function __construct(
        EventRepository $eventRepository,
        AuthController $authController)
    {
        $this->eventRepository  = $eventRepository;
        $this->authController   = $authController;
    }
    
    public function render($planet_id)
    {
        $producefleet = new ViewModel([]);
        $producefleet->setTemplate('include/producefleet');
        $producedefence = new ViewModel([]);
        $producedefence->setTemplate('include/producedefence');
            
        $producesrc = new ViewModel([]);
        $event = $this->checkResourcesBuildingEvent($producesrc, $planet_id);
        $producesrc->setTemplate('include/producesrc');
            
        $produceindustrial = new ViewModel([]);
        $produceindustrial->setTemplate('include/produceindustrial');
        $produceindustrial->setVariable('is_building', false);
        
        $producetech = new ViewModel([]);
        $producetech->setTemplate('include/producetech');
        $planetkeep = new ViewModel([]);
        $planetkeep->setTemplate('include/planetkeep');
        $planetkeep
            ->addChild($producefleet, 'producefleet')
            ->addChild($producedefence, 'producedefence')
            ->addChild($producesrc, 'producesrc')
            ->addChild($produceindustrial, 'produceindustrial')
            ->addChild($producetech, 'producetech');
        return $planetkeep;
    }
    
    private function checkResourcesBuildingEvent(ViewModel &$producesrc, $planetid)
    {
        $is_building = false;
        $event = 0;
        $progress = 0;
        $minutes = "00";
        $seconds = "00";
        $hours = "00";
        try{
            $event = $this->eventRepository->findOneBy(
                    'events.user = ' . $this->authController->getUser()->getId() .
                    ' AND events.target_planet = ' . $planetid .
                    ' AND events.targetBuildingType IS NOT NULL'
                    );
        }
        catch(\Exception $e){
            $event = false;
        }
        if($event){
            $is_building = true;
            $interval = $event->getEventEnd() - $event->getEventBegin();
            $progress = ceil(100 - 100*($event->getEventEnd() - time()) / $interval);
            $begin = $event->getEventBegin();
            $end = $event->getEventEnd();
            $rest = intval($event->getEventEnd() - time());
            if($rest < 0){
                $hours = "00";
                $minutes = "00";
                $seconds = "00";
            }
            else {
                $hours = floor($rest / 3600);
                $minutes = floor(($rest - 3600 * $hours) / 60);
                $seconds = intval($rest - 3600 * $hours - 60 * $minutes);
                if($hours < 10)
                    $hours = "0".$hours;
                if($minutes < 10)
                    $minutes = "0".$minutes;
                if($seconds < 10)
                    $seconds = "0".$seconds;
            }
        }
        $producesrc->setVariable('is_building', $is_building);
        $producesrc->setVariable('event', $event);
        $producesrc->setVariable('progress', $progress);
        $producesrc->setVariable('hours', $hours);
        $producesrc->setVariable('minutes', $minutes);
        $producesrc->setVariable('seconds', $seconds);
        return $event;
    }
    
}
