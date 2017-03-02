<?php

namespace Settings\Controller;

use Settings\Form\SettingsForm;
use Settings\Model\Setting;
use Settings\Model\SettingsCommandInterface;
use Settings\Model\SettingsRepositoryInterface;
use Settings\Model\SettingsTypeofRepositoryInterface;
use InvalidArgumentException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Validator\Db\RecordExists;

use Zend\InputFilter\Factory;

class WriteController extends AbstractActionController
{
    /**
     * @var SettingsCommandInterface
     */
    private $command;

    /**
     * @var SettingsForm
     */
    private $form;

    /**
     * @var SettingsRepositoryInterface
     */
    private $repository;
    
    /**
     * @var SettingsTypeofRepositoryInterface
     */
    private $repositoryTypeof;

    /**
     * @param SettingsCommandInterface $command
     * @param SettingsForm $form
     * @param SettingsRepositoryInterface $repository
     */
    public function __construct(
        SettingsCommandInterface $command,
        SettingsForm $form,
        SettingsRepositoryInterface $repository,
        SettingsTypeofRepositoryInterface $repositoryTypeof
    ) {
        $this->command = $command;
        $this->form = $form;
        $this->repository = $repository;
        $this->repositoryTypeof = $repositoryTypeof;
        $this->form->setRepositoryTypeof ($this->repositoryTypeof);
    }

    public function addAction()
    {
        $request   = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);

        if (! $request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());

        if (! $this->form->isValid()) {
            return $viewModel;
        }

        $setting = $this->form->getData();

        try {
            $setting = $this->command->insertSetting($setting);
        } catch (\Exception $ex) {
            // An exception occurred; we may want to log this later and/or
            // report it to the user. For now, we'll just re-throw.
            throw $ex;
        }

        return $this->redirect()->toRoute(
            'settings/detail',
            ['id' => $setting->getId()]
        );
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id');
        if (! $id) {
            return $this->redirect()->toRoute('setting');
        }

        try {
            $setting = $this->repository->findSetting($id);
        } catch (InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('setting');
        }

        $this->form->bind($setting);
        $viewModel = new ViewModel(['form' => $this->form]);

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $viewModel;
        }
        
        $this->form->setData($request->getPost());
        $inputFilter = $setting->getInputFilter ();
        $factory = new Factory;
        $clause = 'id != ' . (string)intval($request->getPost('setting')['id']);
        $inputFilter->add($factory->createInput(array(
                'name' => 'setting_key',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Db\NoRecordExists',
                        'options' => array(
                            'table' => 'settings',
                            'field' => 'setting_key',
                            'adapter' => $this->repository->getDbAdapter(),
                            'exclude' => $clause,
                            'messages' => array(
                                \Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Ключ ' . $request->getPost('setting')['setting_key'] .' уже имеется в таблице',
                            ),
                        ),
                    ),
                ),
            )));
        
        if (! $this->form->isValid()) {
            return $viewModel;
        }

        $setting = $this->command->updateSetting($setting);
        return $this->redirect()->toRoute(
            'settings/detail',
            ['id' => $setting->getId()]
        );
    }
}
