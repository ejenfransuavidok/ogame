<?php
namespace App\Renderer;

use Zend\View\Model\ViewModel;
use App\Controller\AuthController;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Model\User;
use Entities\Model\Source;
use Entities\Model\SourceRepository;
use Universe\Model\Planet;
use Universe\Classes\PlanetCapacity;


class PopupBigBlackmarketRenderer
{
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    /**
     * @ SourceRepository
     */
    private $sourceRepository;
    
    /**
     * @ PlanetCapacity
     */
    private $planetCapacity;
    
    public function __construct(
        AuthController              $authController,
        SettingsRepositoryInterface $settingsRepository,
        SourceRepository            $sourceRepository,
        PlanetCapacity              $planetCapacity
        )
    {
        $this->authController           = $authController;
        $this->settingsRepository       = $settingsRepository;
        $this->sourceRepository         = $sourceRepository;
        $this->planetCapacity           = $planetCapacity;
    }
    
    public function execute(ViewModel &$template, User $user, Planet $planet)
    {
        if($this->authController->isAuthorized()){
            $sources = [];
            foreach($this->sourceRepository->findAllEntities() as $source){
                $source->getCapacity($planet, $this->planetCapacity);
                $source->getAmount($planet);
                $source->part = intval(100 * $source->amount / $source->capacity);
                $source->diff = $source->capacity - $source->amount;
                $sources [] = $source;
            }
            $template
                ->setVariable('sources', $sources);
        }
    }
    
}
