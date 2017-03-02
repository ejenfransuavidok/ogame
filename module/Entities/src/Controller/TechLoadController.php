<?php

/**
 * 
 * @ author Jigulin V.V.
 * @ 27.02.2017
 * 
 */

namespace Entities\Controller;

require_once (dirname(dirname(__FILE__)) . '/Classes/UploadHandler.php');
require_once (dirname(dirname(__FILE__)) . '/Classes/XmiParser.php');

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Entities\Model\Technology;
use Entities\Model\TechnologyConnection;
use Entities\Model\TechnologyRepository;
use Entities\Model\TechnologyCommand;
use Entities\Model\TechnologyConnectionCommand;
use Entities\Classes\UploadHandler;
use Entities\Classes\XmiParser;

class FailXmiParse extends \Exception
{
}

class TechLoadController extends AbstractActionController
{
    
    /**
     * 
     * @TechnologyRepository
     * 
     */
    private $technologyRepository;
    
    /**
     * 
     * @TechnologyCommand
     * 
     */
    private $technologyCommand;
    
    /**
     * 
     * @TechnologyConnectionCommand
     * 
     */
    private $technologyConnectionCommand;
    
    /**
     * 
     * @classes
     * 
     */
    private $classes;
    
    /**
     * 
     * @normalized classes
     * 
     */
    private $norm_classes;
    
    /**
     * 
     * @dependencies
     * 
     */
    private $dependencies;
    
    
    public function __construct(
        TechnologyRepository $technologyRepository, 
        TechnologyCommand $technologyCommand, 
        TechnologyConnectionCommand $technologyConnectionCommand)
    {
        $this->technologyRepository         = $technologyRepository;
        $this->technologyCommand            = $technologyCommand;
        $this->technologyConnectionCommand  = $technologyConnectionCommand;
    }
    
    public function appendclasses()
    {
        if($this->classes) {
            foreach($this->classes as $class) {
                $attributes = '';
                $description = '';
                if(!method_exists($class, 'attributes')) {
                    throw new FailXmiParse('attributes not fount');
                }
                else {
                    $attributes = $class->{'attributes'}();
                }
                if (!property_exists($class, 'XMI.Classifier.feature') ||
                    !property_exists($class->{'XMI.Classifier.feature'}, 'XMI.Attribute') ||
                    !method_exists($class->{'XMI.Classifier.feature'}->{'XMI.Attribute'}, 'attributes')) {
                    throw new FailXmiParse('XMI.Classifier.feature->XMI.Attribute->attributes not fount');
                }
                else {
                    $description = $class->{'XMI.Classifier.feature'}->{'XMI.Attribute'}->{'attributes'}();
                }
                $technology = new Technology($attributes['name'], $description['comment']);
                $technology = $this->technologyCommand->insertEntity($technology);
                $this->norm_classes [strval($attributes['xmi.id'])] = $technology->getId();
            }
        }
    }
    
    public function appendconnections()
    {
        if($this->dependencies) {
            foreach($this->dependencies as $dependency) {
                $supplier = $this->norm_classes[strval($dependency['supplier'])];
                $client = $this->norm_classes[strval($dependency['client'])];
                $tech_1 = $this->technologyRepository->findEntity(intval($supplier));
                $tech_2 = $this->technologyRepository->findEntity(intval($client));
                $technologyConnection = new TechnologyConnection($tech_1, $tech_2, 1);
                $technologyConnection = $this->technologyConnectionCommand->insertEntity($technologyConnection);
            }
        }
    }
    
    public function execute($file)
    {
        $this->technologyCommand->truncateTable();
        $this->technologyConnectionCommand->truncateTable();
        $parser = new XmiParser();
        $matches = array('//XMI.Class', '//XMI.Dependency');
        $parsing = $parser->parse($file, $matches);
        $this->classes = $parsing [0];
        $this->dependencies = $parsing [1];
        $this->appendclasses();
        $this->appendconnections();
    }
    
    public function techloadAction()
    {
        ob_start();
        error_reporting(E_ALL & ~E_WARNING);
        $upload_handler = new UploadHandler('/\.(xmi)$/i');
        $result = ob_get_contents();
        ob_end_clean();
        if ($result) {
            $files = json_decode($result);            
            foreach($files->files as $file) {
                if(!isset($file->error)) {
                    /// ошибок нет - можно экспортировать в бд
                    try {
                        $this->execute($file->path);
                        die(json_encode(array('result' => 'OK', 'message' => 'Файл технологий загружен успешно')));
                    }
                    catch (\Exception $e) {
                        die(json_encode(array('result' => 'ERR', 'message' => 'Во время загрузки файла произошла ошибка - ' . $e->getMessage())));
                    }
                }
                else {
                    die(json_encode(array('result' => 'ERR', 'error' => $file->error, 'message' => 'Во время загрузки файла произошла ошибка - ' . $file->error)));
                }
            }
        }
    }
}
