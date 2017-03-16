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
require_once (dirname(dirname(__FILE__)) . '/Classes/XmlParser.php');

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Entities\Model\Technology;
use Entities\Model\TechnologyConnection;
use Entities\Model\TechnologyRepository;
use Entities\Model\TechnologyCommand;
use Entities\Model\TechnologyConnectionCommand;
use Entities\Classes\UploadHandler;
use Entities\Classes\XmiParser;
use Entities\Classes\XmlParser;
use Entities\Model\SpaceSheep;
use Entities\Model\SpaceSheepCommand;
use Entities\Model\UserRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\StarRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Entities\Model\Building;
use Entities\Model\BuildingCommand;
use Entities\Model\BuildingRepository;
class FailXmiParse extends \Exception
{
}

class FailXmlParse extends \Exception
{
}

class FormatFailXml extends \Exception
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
    
    /**
     * @var SpaceSheepCommand
     */
    private $spaceSheepCommand;
    
    /**
     * @var GalaxyRepository
     */
    private $galaxyRepository;
    
    /**
     * @var PlanetSystemRepository
     */
    private $planetSystemRepository;
    
    /**
     * @var StarRepository
     */
    private $starRepository;
    
    /**
     * @var PlanetRepository
     */
    private $planetRepository;
    
    /**
     * @var SputnikRepository
     */
    private $sputnikRepository;
    
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * @ var BuildingCommand
     */
    private $buildingCommand;
    
    
    public function __construct(
        TechnologyRepository $technologyRepository, 
        TechnologyCommand $technologyCommand, 
        TechnologyConnectionCommand $technologyConnectionCommand,
        SpaceSheepCommand $spaceSheepCommand,
        GalaxyRepository $galaxyRepository,
        PlanetSystemRepository $planetSystemRepository,
        StarRepository $starRepository,
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository,
        UserRepository $userRepository,
        BuildingCommand $buildingCommand
        )
    {
        $this->technologyRepository         = $technologyRepository;
        $this->technologyCommand            = $technologyCommand;
        $this->technologyConnectionCommand  = $technologyConnectionCommand;
        $this->spaceSheepCommand            = $spaceSheepCommand;
        $this->galaxyRepository             = $galaxyRepository;
        $this->planetSystemRepository       = $planetSystemRepository;
        $this->starRepository               = $starRepository;
        $this->planetRepository             = $planetRepository;
        $this->sputnikRepository            = $sputnikRepository;
        $this->userRepository               = $userRepository;
        $this->buildingCommand              = $buildingCommand;
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
    
    public function sheepsloadAction()
    {
        ob_start();
        error_reporting(E_ALL & ~E_WARNING);
        $upload_handler = new UploadHandler('/\.(xml)$/i');
        $result = ob_get_contents();
        ob_end_clean();
        if ($result) {
            $files = json_decode($result);            
            foreach($files->files as $file) {
                if(!isset($file->error)) {
                    /// ошибок нет - можно экспортировать в бд
                    try {
                        
                        $this->sheepsLoadExecute($file->path);
                        
                        die(json_encode(array('result' => 'OK', 'message' => 'Файл космических кораблей загружен успешно')));
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
        die(json_encode(array('result' => 'ERR', 'message' => 'Во время загрузки файла произошла ошибка')));
    }
    
    public function buildingsloadAction()
    {
        ob_start();
        error_reporting(E_ALL & ~E_WARNING);
        $upload_handler = new UploadHandler('/\.(xml)$/i');
        $result = ob_get_contents();
        ob_end_clean();
        if ($result) {
            $files = json_decode($result);            
            foreach($files->files as $file) {
                if(!isset($file->error)) {
                    /// ошибок нет - можно экспортировать в бд
                    try {
                        $this->buildingsLoadExecute($file->path);
                        die(json_encode(array('result' => 'OK', 'message' => 'Файл ресурсных сооружений загружен успешно')));
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
        die(json_encode(array('result' => 'ERR', 'message' => 'Во время загрузки файла произошла ошибка')));
    }
     
    private function buildingsLoadExecute($file)
    {
        $keys = array(
            'name', 'consume_metall', 'consume_heavygas', 'consume_ore', 'consume_hydro',
            'consume_titan', 'consume_darkmatter', 'consume_redmatter', 'consume_anti',
            'consume_electricity', 'produce_metall', 'produce_heavygas', 'produce_ore',
            'produce_hydro', 'produce_titan', 'produce_darkmatter', 'produce_redmatter',
            'produce_anti', 'produce_electricity', 'factor');
        $parser = new XmlParser();
        $result = $parser->parse($file);
        if(!is_array($result) && count($result) != 20) {
            throw new FormatFailXml('data is not array or array size != 20');
        }
        else {
            $entities = array();
            for($i=0; $i<count($result); $i++) {
                $row = $result [$i];
                $key = $row[0];
                if($key != $keys[$i]) {
                    throw new FormatFailXml("column title must be " . $keys[$i] . ", but " . $col[0] . " given.");
                }
                else {
                    for($j=1; $j<count($row); $j++) {
                        $value = $row[$j];
                        $entities [$j-1][$key] = $value;
                    }
                }
            }
            if(is_array($entities)) {
                $this->buildingCommand->truncateTable();
                foreach($entities as $one) {
                    $level = 1;
                    $owner = $this->userRepository->findEntity(1);
                    $planet = $this->planetRepository->findEntity(1);
                    $building = new Building(
                        $one['name'],
                        null,
                        $planet,
                        null,
                        $owner,
                        $level,
                        Building::$BUILDING_RESOURCE,
                        time(),
                        $one['factor'],
                        $one['produce_metall'],
                        $one['produce_heavygas'],
                        $one['produce_ore'],
                        $one['produce_hydro'],
                        $one['produce_titan'],
                        $one['produce_darkmatter'],
                        $one['produce_redmatter'],
                        $one['produce_anti'],
                        $one['produce_electricity'],
                        $one['consume_metall'],
                        $one['consume_heavygas'],
                        $one['consume_ore'],
                        $one['consume_hydro'],
                        $one['consume_titan'],
                        $one['consume_darkmatter'],
                        $one['consume_redmatter'],
                        $one['consume_anti'],
                        $one['consume_electricity'],
                        $one['factor']
                    );
                    $building = $this->buildingCommand->insertEntity($building);
                }
            }
        }
    }
    
    private function sheepsLoadExecute($file)
    {   
        $keys = array(
            'name', 'description', 'speed', 'capacity', 'fuel_consumption', 
            'fuel_tank_size', 'attak_power', 'rate_of_fire', 'the_number_of_attak_targets', 
            'sheep_size', 'protection', 'number_of_guns', 'construction_time', 
            'fuel_rest', 'galaxy', 'planetSystem', 'star', 'planet', 'sputnik', 'owner');
        $parser = new XmlParser();
        $result = $parser->parse($file);
        if(!is_array($result) && count($result) != 20) {
            throw new FormatFailXml('data is not array or array size != 20');
        }
        else {
            $entities = array();
            for($i=0; $i<count($result); $i++) {
                $row = $result [$i];
                $key = $row[0];
                if($key != $keys[$i]) {
                    throw new FormatFailXml("column title must be " . $keys[$i] . ", but " . $col[0] . " given.");
                }
                else {
                    for($j=1; $j<count($row); $j++) {
                        $value = $row[$j];
                        $entities [$j-1][$key] = $value;
                    }
                }
            }
            if(is_array($entities)) {
                $this->spaceSheepCommand->truncateTable();
                foreach($entities as $one) {
                    try {
                        $galaxy = $this->galaxyRepository->findEntity(intval($one['galaxy']));
                    }
                    catch(\Exception $e) {
                        $galaxy = null;
                    }
                    try {
                        $planetSystem = $this->planetSystemRepository->findEntity(intval($one['planetSystem']));
                    }
                    catch(\Exception $e) {
                        $planetSystem = null;
                    }
                    try {
                        $star = $this->starRepository->findEntity(intval($one['star']));
                    }
                    catch(\Exception $e) {
                        $star = null;
                    }
                    try {
                        $planet = $this->planetRepository->findEntity(intval($one['planet']));
                    }
                    catch(\Exception $e) {
                        $planet = null;
                    }
                    try {
                        $sputnik = $this->sputnikRepository->findEntity(intval($one['sputnik']));
                    }
                    catch(\Exception $e) {
                        $sputnik = null;
                    }
                    try {
                        $owner = $this->userRepository->findEntity(intval($one['owner']));
                    }
                    catch(\Exception $e) {
                        $owner = null;
                    }
                    $spaceSheep = new SpaceSheep(
                        $one['name'],
                        $one['description'],
                        $one['speed'],
                        $one['capacity'],
                        $one['fuel_consumption'],
                        $one['fuel_tank_size'],
                        $one['attak_power'],
                        $one['rate_of_fire'],
                        $one['the_number_of_attak_targets'],
                        $one['sheep_size'],
                        $one['protection'],
                        $one['number_of_guns'],
                        $one['construction_time'],
                        $one['fuel_rest'],
                        $galaxy,
                        $planetSystem,
                        $star,
                        $planet,
                        $sputnik,
                        $owner,
                        null//event
                        );
                    $spaceSheep = $this->spaceSheepCommand->insertEntity($spaceSheep);
                }
            }
        }
    }
    
}
