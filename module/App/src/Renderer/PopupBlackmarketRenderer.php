<?php
namespace App\Renderer;

use Zend\View\Model\ViewModel;
use App\Controller\AuthController;
use Settings\Model\SettingsRepositoryInterface;
use Entities\Model\User;

class PopupBlackmarketRenderer
{
    /**
     * @ AuthController
     */
    private $authController;
    
    /**
     * @SettingsRepositoryInterface $settingsRepository
     */
    private $settingsRepository;
    
    public function __construct(
        AuthController              $authController,
        SettingsRepositoryInterface $settingsRepository
        )
    {
        $this->authController           = $authController;
        $this->settingsRepository       = $settingsRepository;
    }
    
    public function execute(ViewModel &$template, User $user)
    {
        if($this->authController->isAuthorized()){
            //$spacesheeps = $this->spaceSheepRepository->findBy('spacesheeps.owner = ' . $user->getId() . ' AND spacesheeps.planet = ' . $currentPlanet->getId())->buffer();
            $donate_price = intval($this->settingsRepository->findSettingByKey ('DONATE_ITEM_PRICE')->getText());
            $dollar_euro_course = floatval($this->settingsRepository->findSettingByKey ('DOLLAR_EURO_COURSE')->getText());
            $template
                ->setVariable('donate_price', $donate_price)
                ->setVariable('dollar_euro_course', $dollar_euro_course)
                ->setVariable('money', $user->getMoney());
        }
    }
    
}
