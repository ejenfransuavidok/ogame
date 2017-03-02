<?php
namespace Settings\Controller;

use Settings\Model\Setting;
use Settings\Model\SettingsCommandInterface;
use Settings\Model\SettingsRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DeleteController extends AbstractActionController
{
    /**
     * @var PostCommandInterface
     */
    private $command;

    /**
     * @var PostRepositoryInterface
     */
    private $repository;

    /**
     * @param PostCommandInterface $command
     * @param PostRepositoryInterface $repository
     */
    public function __construct(
        SettingsCommandInterface $command,
        SettingsRepositoryInterface $repository
    ) {
        $this->command = $command;
        $this->repository = $repository;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if (! $id) {
            return $this->redirect()->toRoute('settings');
        }

        try {
            $setting = $this->repository->findSetting($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('settings');
        }

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return new ViewModel(['setting' => $setting]);
        }

        if ($id != $request->getPost('id')
            || 'Delete' !== $request->getPost('confirm', 'no')
        ) {
            return $this->redirect()->toRoute('settings');
        }

        $setting = $this->command->deleteSetting($setting);
        return $this->redirect()->toRoute('settings');
    }
}
