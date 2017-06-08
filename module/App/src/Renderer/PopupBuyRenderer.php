<?php
namespace App\Renderer;

use Zend\View\Model\ViewModel;
use App\Controller\AuthController;
use Entities\Model\User;
use Settings\Model\SettingsRepositoryInterface;

class PopupBuyRenderer
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
            $template
                ->setVariable('money', $user->getMoney());
        }
    }
    
}
