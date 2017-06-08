<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Cron\Classes\Cbr;

class CbrController extends AbstractActionController
{
    
    /**
     * @ Cbr
     */
    private $cbr;
    
    public function __construct(Cbr $cbr)
    {
        $this->cbr = $cbr;
    }
    
    public function cbrAction()
    {
        $view = new ViewModel([]);
        $view->setTerminal(true);
        $this->cbr->execute();
        $view->setVariable('data', array('result' => 'OK'));
        return $view;
    }
    
}
