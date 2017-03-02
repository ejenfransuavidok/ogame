<?php

namespace Settings\Controller;

use Settings\Model\SettingsRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{
    private $settingsRepository;

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }
    
    public function indexAction()
    {
        $paginator = $this->settingsRepository->findAllSettings (true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);
        return new ViewModel([
            'settings' => $paginator,//$this->settingsRepository->findAllSettings(),
        ]);
    }
    
    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $setting = $this->settingsRepository->findSetting($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('settings');
        }

        return new ViewModel([
            'setting' => $setting,
        ]);
        
    }
}
