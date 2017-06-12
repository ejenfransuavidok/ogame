<?php

namespace Entities\Classes;

use Entities\Model\EventRepository;

class EventTypes
{
    
    public static $FLOT_RELOCATION                  = 1;
    public static $FLEET_RELOCATION_BIDIRECTIONAL   = 4;
    public static $DO_BUILD_RESOURCES               = 2;
    public static $DO_BUILD_INDUSTRIAL              = 3;
    
    public static $BASE_FOR_BUILDING                = 0;
    public static $MAX_FOR_BUILDING                 = 1000000;
    
    static public function calcEventIdForBuildings($first, $second)
    {
        return self::$BASE_FOR_BUILDING + $first * 1000 + $second;
    }
    
    static public function getBuildingEvents(EventRepository $eventRepository)
    {
        return
            $eventRepository->findAllEntities('events.event_type < ' . self::$MAX_FOR_BUILDING)->buffer();
    }
}
