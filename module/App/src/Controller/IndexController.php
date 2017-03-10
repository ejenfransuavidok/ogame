<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 10.03.2017
 * 
 */

namespace App\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\AdapterInterface;

class IndexController extends AbstractActionController
{
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;
    
    public function __construct(AdapterInterface $db)
    {
        $this->dbAdapter = $db;
    }
    
    public function indexAction()
    {
        $layout = $this->layout();
        
        $layout->setTemplate('app/layout');
        
        return new ViewModel([]);
    }
    
}
