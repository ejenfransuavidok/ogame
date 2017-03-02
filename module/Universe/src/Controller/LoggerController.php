<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 07.02.2017
 * 
 */

namespace Universe\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class LoggerController extends AbstractActionController
{
    /**
     * 
     * @string
     * 
     */
    private $logpath;
    
    /**
     * 
     * @file
     * 
     */
    private $stream;
    
    public function __construct()
    {
        $this->logpath = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/logs/universe-generation.log";
        $this->stream = @fopen($this->logpath, 'r', false);
        if (! $this->stream) {
            throw new \Exception('Failed to open stream ' . $this->logpath);
        }
    }
    
    public function loggerAction()
    {
        $output = array();
        while (($buffer = fgets($this->stream, 4096)) !== false) {
            $output [] = $buffer;
        }
        if (!feof($this->stream)) {
            throw new \Exception('Error: unexpected fgets() fail\n');
        }
        $result = new JsonModel(array(
            'messages'  => implode('<br>',$output),
            'success'   =>true,
        ));
 
        return $result;
    }
    
}
