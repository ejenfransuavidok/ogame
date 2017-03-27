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
use Zend\Log\Writer\Stream;
use Zend\Log\Logger;
use Universe\Geometry\Point;
use Universe\Model\Planet;
use Universe\Model\Star;
use Universe\Model\PlanetSystem;
use Universe\Model\Galaxy;
use Universe\Model\Sputnik;
use Universe\Model\StarType;
use Universe\Model\GalaxyCommand;
use Universe\Model\PlanetSystemCommand;
use Universe\Model\StarCommand;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikCommand;
use Universe\Model\StarTypeCommand;
use Universe\Model\StarTypeRepository;
use Universe\Model\PlanetType;
use Universe\Model\PlanetTypeCommand;
use Universe\Model\PlanetTypeRepository;
use Universe\Model\GalaxyRepository;
use Universe\Model\PlanetSystemRepository;
use Universe\Model\StarRepository;
use Universe\Model\PlanetRepository;
use Universe\Model\SputnikRepository;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Zend\Math\Rand;


class FailPlanetsTypesParse extends \Exception
{
}

class FailPlanetsTypesIsNull extends \Exception
{
}

define ('DEBUG', 'WORKING');

class CreatorController extends AbstractActionController
{
    /**
     * 
     * @Zend\Log\Logger
     * 
     */
    private $logger;
    
    /**
     * 
     * @Zend\Log\Writer
     * 
     */
    private $writer;
    
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
    
    /**
     * 
     * @Galaxy list
     * 
     */
     private $galaxies = array();
     
     /**
      * 
      * @PlanetSystem list
      * 
      */
     private $planet_systems = array();
     
     /**
      * 
      * @Planet list
      * 
      */
     private $planets = array();
     
     /**
      * 
      * @Star list
      * 
      */
     private $stars = array();
    
    /**
     * 
     * @SettingsRepositoryInterface $settingsRepository
     * 
     */
    private $settingsRepository;
    
    /**
     * 
     * @GalaxyCommand $galaxyCommand
     * 
     */
    private $galaxyCommand;
    
    /**
     * 
     * @PlanetSystemCommand $planetSystemCommand
     * 
     */
    private $planetSystemCommand;
    
    /**
     * 
     * @StarCommand $starCommand
     * 
     */
    private $starCommand;
    
    /**
     * 
     * @PlanetCommand $planetCommand
     * 
     */
    private $planetCommand;
    
    /**
     * 
     * @SputnikCommand $sputnikCommand
     * 
     */
    private $sputnikCommand;
    
    /**
     * 
     * @StarTypeCommand $starTypeCommand
     * 
     */
    private $starTypeCommand;
    
    /**
     * 
     * @StarTypeRepository $starTypeRepository
     * 
     */
    private $starTypeRepository;
    
    /**
     * 
     * @PlanetTypeCommand $planetTypeCommand
     * 
     */
    private $planetTypeCommand;
    
    /**
     * 
     * @PlanetTypeRepository $planetTypeRepository
     * 
     */
    private $planetTypeRepository;
    
    /**
     * 
     * @GalaxyRepository $galaxyRepository
     * 
     */
    private $galaxyRepository;
    
    /**
     * 
     * @PlanetSystemRepository planetSystemRepository
     * 
     */
    private $planetSystemRepository;
    
    /**
     * 
     * @StarRepository starRepository
     * 
     */
    private $starRepository;
    
    /**
     * 
     * @PlanetRepository planetRepository
     * 
     */
    private $planetRepository;
    
    /**
     * 
     * @SputnikRepository sputnikRepository
     * 
     */
    private $sputnikRepository;
    
    /**
     * 
     * @all Stars types probabilities
     * 
     */
    private $all_stars_types_probabilities;
    
    /**
     * 
     * @ lock-file
     * 
     */
    private $lock;
    
    /**
     * PlanetType
     */
    private $planets_types_arr = array();
    
    /**
     * 
     */
    private $planets_types_probabilities = array();
    
    
    public function __construct(
        SettingsRepositoryInterface $settingsRepository, 
        GalaxyCommand $galaxyCommand,
        PlanetSystemCommand $planetSystemCommand,
        StarCommand $starCommand,
        PlanetCommand $planetCommand,
        SputnikCommand $sputnikCommand,
        StarTypeCommand $starTypeCommand,
        StarTypeRepository $starTypeRepository,
        PlanetTypeCommand $planetTypeCommand,
        PlanetTypeRepository $planetTypeRepository,
        GalaxyRepository $galaxyRepository,
        PlanetSystemRepository $planetSystemRepository,
        StarRepository $starRepository,
        PlanetRepository $planetRepository,
        SputnikRepository $sputnikRepository)
    {
        $this->settingsRepository       = $settingsRepository;
        $this->starTypeRepository       = $starTypeRepository;
        $this->galaxyRepository         = $galaxyRepository;
        $this->planetSystemRepository   = $planetSystemRepository;
        $this->starRepository           = $starRepository;
        $this->planetRepository         = $planetRepository;
        $this->sputnikRepository        = $sputnikRepository;
        $this->galaxyCommand            = $galaxyCommand;
        $this->planetSystemCommand      = $planetSystemCommand;
        $this->starCommand              = $starCommand;
        $this->planetCommand            = $planetCommand;
        $this->sputnikCommand           = $sputnikCommand;
        $this->starTypeCommand          = $starTypeCommand;
        $this->starTypeRepository       = $starTypeRepository;
        $this->planetTypeCommand        = $planetTypeCommand;
        $this->planetTypeRepository     = $planetTypeRepository;

        /**
         * 
         * @отчистка таблиц перед заполнением
         * 
         */
        $this->clearTables();
        
        $this->logpath = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/logs/universe-generation.log";
        $this->stream = @fopen($this->logpath, 'w+', false);
        if (! $this->stream) {
            throw new \Exception('Failed to open stream ' . $this->logpath);
        }
        $this->writer = new Stream($this->stream);
        $this->logger = new Logger();
        $this->logger->addWriter($this->writer);
    }
    
    /**
     * 
     * @ clear all tables
     * 
     */
    public function clearTables()
    {
        if(DEBUG != 'DEBUG')
        {
            $this->galaxyCommand->truncateTable();
            $this->planetSystemCommand->truncateTable();
            $this->starCommand->truncateTable();
            $this->planetCommand->truncateTable();
            $this->sputnikCommand->truncateTable();
        }
        $this->starTypeCommand->truncateTable();
        $this->planetTypeCommand->truncateTable();
    }
    
    public function CreatorAction()
    {
        /**
         * 
         * @тест на наличие .lock.zf файла
         * 
         */
        $this->lock = dirname(__FILE__) . '/' . '.lock.zf';
        if(file_exists($this->lock))
        {
            $this->logger->info('> Предыдущий поток генерации не окончен!!!');
            die();
        }
        else
        {
            /**
             * 
             * @сгенерируем lock файл
             * 
             */
            file_put_contents($this->lock, 'lock');
        }
        
        /**
         * 
         * @задаем время работы скрипта
         * 
         */
        set_time_limit(120);
                
        /**
         * 
         * @Считывание исходных данных
         * 
         */
        $this->ReadSettings();
        
        /**
         * 
         * @Итерация галактик с генерацией
         * 
         */
        if(DEBUG != 'DEBUG')
        {
            $this->GalaxiesIterator();
        }
        /**
         * 
         * @удалим lock файл
         * 
         */
        unlink($this->lock);
        
        $this->reportGenerator();
        
        $result = new JsonModel(array(
            'messages'  => 'start',
            'success'   =>true,
        ));
        return $result;
    }
    
    /**
     * 
     * @return void
     * 
     */
    public function reportGenerator()
    {
       $galaxies = $this->galaxyRepository->findAllEntities();
       $this->logger->info('> Всего сгенерировано галактик - ' . count($galaxies));
       $planet_systems = $this->planetSystemRepository->findAllEntities();
       $this->logger->info('> Всего сгенерировано планетных систем - ' . count($planet_systems));
       $stars = $this->starRepository->findAllEntities();
       $this->logger->info('> Всего сгенерировано звезд - ' . count($stars));
       $star_types = $this->starTypeRepository->findAllEntities();
       foreach($star_types as $star_type) {
           $stars = $this->starRepository->findAllEntities('stars.star_type = ' . $star_type->getId());
           $this->logger->info('> Звезд [' . $star_type->get_color_rus() . ', класса ' .$star_type->get_star_class()  . '] - ' . count($stars));
       }
       $planets = $this->planetRepository->findAllEntities();
       $this->logger->info('> Всего сгенерировано планет - ' . count($planets));
       $sputniks = $this->sputnikRepository->findAllEntities();
       $this->logger->info('> Всего сгенерировано спутников - ' . count($sputniks));
    }
    
    /**
     * 
     * @void
     * 
     */
    public function GalaxiesIterator()
    {
        $this->logger->info('> Начало генерации галактик...');
        $this->GALAXIES_COUNT = $this->GetAverageRandom($this->COUNT_GALAXIES_MEDIAN, $this->COUNT_GALAXIES_DELTA);
        $this->logger->info('> Всего будет сформировано ' . $this->GALAXIES_COUNT . ' галактик...');
        $galaxy_basis = 0;
        
        for($i = 0; $i < $this->GALAXIES_COUNT; $i++)
        {
            /**
             * 
             * 
             * @тест lock файла
             * 
             */
            if(! file_exists($this->lock))
            {
                $this->logger->info('> Lock-файл не найден!!!');
                die();
            }
            
            /**
             * 
             * 
             * @генерация галактики
             * 
             */
            $galaxy = new Galaxy(null, null, null, 0);
            
            $galaxy->setRandomName();
            $this->logger->info('> Формирование галактики ('.$i.') ' . $galaxy->getName());
            $galaxy->setBasis($galaxy_basis);
            
            /**
             * 
             * @сохраним галактику в базу
             * 
             */
            $galaxy = $this->galaxyCommand->insertEntity($galaxy);
            
            /**
             * 
             * @сгенерируем галактику и получим размер
             * 
             */
            $galaxy_size = $this->PlanetSystemsIterator($galaxy);
            
            /**
             * 
             * @установим размер новой галактики
             * 
             */
            $galaxy->setSize($galaxy_size);
            
            /**
             * 
             * @определим расстояние до следующей галактики
             * 
             */
            $galaxy_gap_between = $galaxy_size + $this->GetGapBetweenGalaxies($galaxy_size);
            /**
             * 
             * @установим базис следующей галактики
             * 
             */
            $galaxy_basis += $galaxy_gap_between;
            
            /**
             * 
             * @обновим галактику
             * 
             */
            $this->galaxyCommand->updateEntity($galaxy);
            
        }
        $this->logger->info('> Окончание генерации галактик...');
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function GetGapBetweenGalaxies($galaxy_size)
    {
        $GALAXY_GAP = 1.5;
        return floor($galaxy_size / $GALAXY_GAP);
    }
    
    /**
     * 
     * @return int
     * 
     */
    public function GetGapBetweenPlanetSystems($planet_system_size)
    {
        $PLANET_SYSTEMS_GAP = 1.5;
        return floor($planet_system_size / $PLANET_SYSTEMS_GAP);
    }
    
    /**
     * 
     * @param Galaxy
     * @return int
     * 
     */
    public function PlanetSystemsIterator(Galaxy &$parent)
    {
        $this->PLANET_SYSTEMS_COUNT = $this->GetAverageRandom($this->PLANET_SYSTEMS_COUNT_IN_GALAXY_MEDIAN, $this->PLANET_SYSTEMS_COUNT_IN_GALAXY_DELTA);
        /**
         * 
         * @базис первой планетной системы равен базису галактики
         * 
         */
        $planet_system_basis = $parent->GetBasis();
        
        for ($i = 0; $i < $this->PLANET_SYSTEMS_COUNT; $i++)
        {
            $planet_system = new PlanetSystem(null,null,null,null,null,null,$i+1);
            $planet_system->setRandomName();
            /**
             * 
             * @ установим родительскую галактику планетной системы
             * 
             */
            $planet_system->setGalaxy($parent);
            
            /**
             * 
             * @установим базис планетной системы
             * 
             */
            $planet_system->setBasis($planet_system_basis);
            
            /**
             * 
             * 
             * @сгенерируем звезду
             * 
             */
            $star = new Star(null,null,null,null,null,null);
            
            /**
             * 
             *@ назначение типа звездам 
             * 
             */
            $this->starTypization($star);
             
            /**
             * 
             * @сгенерируем имя звезды
             * 
             */
            $star->setRandomName();
             
            /**
             * 
             * @координата звезды равна базису системы в 1-мерном мире
             * 
             */
            $star->setCoordinate($planet_system_basis);
            
            /**
             * 
             * @установка родительской планетной системы
             * 
             */
            $star->setCelestialParent($planet_system); 
             
            /**
             * 
             * @установка звезды в планетную систему
             * 
             */
            $planet_system->setStar($star);
            
            /**
             * 
             * @установка размера планетной системы (всегда один и тот же)
             * 
             */
            $planet_system->setSize($this->PLANET_SYSTEM_SIZE);
            
            /**
             * 
             * @запись в базу планетной системы
             * 
             */
            $planet_system = $this->planetSystemCommand->insertEntity($planet_system);
            
            /**
             * 
             * @сгенерируем планеты
             * 
             */
            $this->PlanetsIterator($planet_system);
            
             /**
             * 
             * @определим расстояние до следующей планетной системы
             * 
             */
            $planet_system_gap_between = $this->PLANET_SYSTEM_SIZE + $this->GetGapBetweenPlanetSystems($this->PLANET_SYSTEM_SIZE);
            
            /**
             * 
             * @установим базис следующей планетной системы
             * 
             */
            $planet_system_basis += $planet_system_gap_between;
            
            /**
             * 
             * @запись в базу звезды
             * 
             */
            $star = $this->starCommand->insertEntity($star);
            
            /**
             * 
             * @обновим звезду из бд
             * 
             */
            $planet_system->setStar($star);
            
            /**
             * 
             * @обновим родительскую планетную систему из бд
             * 
             */
            $star->setCelestialParent($planet_system);
            
            /**
             * 
             * @обновим планетную систему
             * 
             */
            $this->planetSystemCommand->updateEntity($planet_system);
            
            /**
             * 
             * @обновим звезду
             * 
             */
            $this->starCommand->updateEntity($star);
            
            /**
             * 
             * @добавим в звезду дополнительные параметры (цвет, температура, тип)
             * 
             */
            $this->add2StarAdditionalParameters($star);
            
        }
        return $this->PLANET_SYSTEMS_COUNT * $this->PLANET_SYSTEM_SIZE;
    }
    
    /**
     * @ 
     */
    public function setPlanetsTypesProbabilities()
    {
        if(!count($this->planets_types_arr)){
            throw new FailPlanetsTypesIsNull();
        }
        else{
            foreach($this->planets_types_arr as $planet_type){
                for($i=0; $i<intval($planet_type->getProbability()); $i++){
                    $this->planets_types_probabilities[] = $planet_type;
                }
            }
            //print_r($this->planets_types_probabilities); die();
        }
    }
    
    /**
     * @ set planet type
     */
    public function setPlanetType(&$planet)
    {
        $min = 0;
        $max = count($this->planets_types_probabilities) - 1;
        $rnd = Rand::getInteger($min, $max);
        $planet_type = $this->planets_types_probabilities[$rnd];
        $planet->setType($planet_type);
    }
    
    /**
     * 
     * @param PlanetSystem
     * 
     */
    public function PlanetsIterator(PlanetSystem &$parent)
    {
        $this->PLANET_COUNT = $this->GetAverageRandom($this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN, $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN_DELTA);
        $planet_basis = $parent->GetBasis();
        for($i = 0; $i < $this->PLANET_COUNT; $i++)
        {
            /**
             * 
             * @определили расстояние до следующей планеты
             * 
             */
            $this->PLANETS_DISTANCE = $this->GetAverageRandom($this->PLANETS_BETWEEN_DISTANCE_MEDIAN, $this->PLANETS_BETWEEN_DISTANCE_MEDIAN_DELTA);
            
            /**
             * 
             * @определили базис следующей планеты
             * 
             */
            $planet_basis += $this->PLANETS_DISTANCE;
            
            $planet = new Planet(null,null,null,null,null,null,null,null,1,1,1,1,1,1,1,1,1,null);
            
            /**
             * 
             * @определим имя планеты
             * 
             */
             $planet->setRandomName();
             
            /**
             * 
             * @определим позицию планеты в планетной системе
             * 
             */
            $planet->setPosition($i + 1);
            
            /**
             * 
             * @определим родительскую планетную систему
             * 
             */
            $planet->setCelestialParent($parent);
            
            /**
             * 
             * @определим координату планеты (базис)
             * 
             */
            $planet->setCoordinate($planet_basis);
            
            
            /**
             * @ установка типа планеты
             */
            $this->setPlanetType($planet);
            
            /**
             * 
             * @запись планеты в базу данных
             * 
             */
            $planet = $this->planetCommand->insertEntity($planet);
            
            /**
             * 
             * @генерация спутников планеты
             * 
             */
            $this->SputniksIterator($parent, $planet);
        }
    }
    
    /**
     * 
     * @param PlanetSystem, Planet
     * 
     */
    public function SputniksIterator(PlanetSystem &$parent, Planet &$parent_planet)
    {
        $integer = Rand::getInteger(0, 100);
        if($integer <= $this->PROBABILITY_OF_AVAILABILITY_SPUTNIKS)
        {
            $this->SPUTNIKS_COUNT = $this->GetAverageRandom($this->PLANET_COUNT_SPUTNIKS_MEDIAN, $this->PLANET_COUNT_SPUTNIKS_MEDIAN_DELTA);
            for($i = 0; $i < $this->SPUTNIKS_COUNT; $i++)
            {
                $sputnik = new Sputnik(null,null,null,null,null,null,null,null,1,1,1,1,1,1,1,1,1,null);
                
                /**
                 * 
                 * @определим имя спутника
                 * 
                 */
                $sputnik->setRandomName();
                
                /**
                 * 
                 * @определим позицию спутника в спутниковой системе
                 * 
                 */
                $sputnik->setPosition($i + 1);
                
                /**
                 * 
                 * @определим расстояние спутника до планеты
                 * 
                 */
                $sputnik->setDistance($i + 1);
                
                /**
                 * 
                 * @определим родительскую планету
                 * 
                 */
                $sputnik->setParentPlanet($parent_planet);
                
                /**
                 * 
                 * @определим родительскую планетную систему
                 * 
                 */
                $sputnik->setCelestialParent($parent);
                
                /**
                 * @ установка типа спутника
                 */
                $this->setPlanetType($sputnik);
                
                /**
                 * 
                 * @запись спутника в базу данных
                 * 
                 */
                $sputnik = $this->sputnikCommand->insertEntity($sputnik);
            }
        }
    }
    
    /**
     * 
     * @param int, int
     * 
     */
    private function GetAverageRandom ($average, $delta)
    {
        return Rand::getInteger($average - $delta, $average + $delta);
    }
    
    private function ReadSettings()
    {
        /**
         * 
         * @считывание парметров звезд
         * 
         */
        $this->Read_SPECTRAL_STARS_CONFIGURATION();
        
        /**
         * @ считывание параметров планет
         */
        $this->readPlanetsTypesConfig();
        
        /**
         * @ готовим массив вероятностей
         */
        $this->setPlanetsTypesProbabilities();
        
        /**
         * 
         * @сохранения в массиве вероятностей типов звезд
         * 
         */
        foreach($this->starTypeRepository->findAllEntities() as $star_type)
        {
            for($i = 0; $i < $star_type->get_part(); $i++)
            {
                $this->all_stars_types_probabilities [] = $star_type;
            }
        }
        
        /**
         * 
         * @ случайная сортировка массива типов звезд
         * 
         */
        shuffle($this->all_stars_types_probabilities);
        
        $this->logger->info('> Считывание исходных данных...');
        /**
         *  @Среднее количество планет в планетной системе 
         */
        $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN = $this->settingsRepository->findSettingByKey ('PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN')->getText();
        /**
         *  @Максимальная величина отклонения количества планет в планетной системе от среднего значения  
         */
        $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN_DELTA = $this->settingsRepository->findSettingByKey ('PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN_DELTA')->getText();
        /**
         * @ Среднее значение расстояния между планетами в планетной системе 
         */
        $this->PLANETS_BETWEEN_DISTANCE_MEDIAN = $this->settingsRepository->findSettingByKey ('PLANETS_BETWEEN_DISTANCE_MEDIAN')->getText();
        /**
         * @ Отклонение от среднего значение расстояние между планетами в планетной системе
         */
        $this->PLANETS_BETWEEN_DISTANCE_MEDIAN_DELTA = $this->settingsRepository->findSettingByKey ('PLANETS_BETWEEN_DISTANCE_MEDIAN_DELTA')->getText();
        /**
         * 
         * @ Среднее количество планетных систем в галактике
         * 
         */
        $this->PLANET_SYSTEMS_COUNT_IN_GALAXY_MEDIAN = $this->settingsRepository->findSettingByKey ('PLANET_SYSTEMS_COUNT_IN_GALAXY_MEDIAN')->getText();
        /**
         * 
         * @ Максимальное отклонение от среднего количества планетных систем в галактике
         * 
         */
        $this->PLANET_SYSTEMS_COUNT_IN_GALAXY_DELTA = $this->settingsRepository->findSettingByKey ('PLANET_SYSTEMS_COUNT_IN_GALAXY_DELTA')->getText();
        
        /**
         * @максимальное расстояние между планетами в планетной системе
         */
        $this->PLANETS_BETWEEN_DISTANCE_MAX = $this->PLANETS_BETWEEN_DISTANCE_MEDIAN + $this->PLANETS_BETWEEN_DISTANCE_MEDIAN_DELTA;
        
        /**
         * @максимальное количество планет в планетной системе
         */
        $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MAX = $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN + $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MEDIAN_DELTA;
        
        /**
         * @размер планетной системы, планетная система представляет из себя квадрат, со стороной, равной
         * произведению максимального расстояния между планетами на максимальное число планет
         */
        $this->PLANET_SYSTEM_SIZE = $this->PLANETS_BETWEEN_DISTANCE_MAX * $this->PLANET_COUNT_IN_PLANETS_SYSTEM_MAX;
        
        /**
         * 
         * @Среднее количество галактик во вселенной
         * 
         */
        $this->COUNT_GALAXIES_MEDIAN = $this->settingsRepository->findSettingByKey ('COUNT_GALAXIES_MEDIAN')->getText();
        
        /**
         * 
         * @Максимальное отклонение от среднего количества галактик во вселенной 
         * 
         */
        $this->COUNT_GALAXIES_DELTA  = $this->settingsRepository->findSettingByKey ('COUNT_GALAXIES_DELTA')->getText();
        
        /**
         * 
         * @ Среднее значение спутников у планет 
         * 
         */
        $this->PLANET_COUNT_SPUTNIKS_MEDIAN = $this->settingsRepository->findSettingByKey ('PLANET_COUNT_SPUTNIKS_MEDIAN')->getText();
        
        /**
         * 
         * @  Отклонение от среднего значения количества спутников у планет 
         *
         */
        $this->PLANET_COUNT_SPUTNIKS_MEDIAN_DELTA  = $this->settingsRepository->findSettingByKey ('PLANET_COUNT_SPUTNIKS_MEDIAN_DELTA')->getText();
        
        /**
         * 
         * @   Вероятность появления спутников у планет (в процентах)
         *
         */
        $this->PROBABILITY_OF_AVAILABILITY_SPUTNIKS = $this->settingsRepository->findSettingByKey ('PROBABILITY_OF_AVAILABILITY_SPUTNIKS')->getText(); 
    }
    
    /**
     * 
     * добавление к звезде дополнительных параметров
     * 
     */
    public function add2StarAdditionalParameters(&$star)
    {
    }
    
    public function Read_SPECTRAL_STARS_CONFIGURATION()
    {
        /**
         * 
         * @ считывание SPECTRAL_STARS_CONFIGURATION 
         * 
         */
        $this->SPECTRAL_STARS_CONFIGURATION = $this->settingsRepository->findSettingByKey ('SPECTRAL_STARS_CONFIGURATION')->getText();
        foreach(explode("|", $this->SPECTRAL_STARS_CONFIGURATION) as $item)
        {
            $star_type = new StarType(null,null,null,null,null,null,null,null);
            $items = explode(",", $item);
            /**
             * 
             * @blue-white,бело-голубой,10000,30000,B,22
             * 
             */
            $star_type->set_color_eng(trim($items[0]));
            $star_type->set_color_rus(trim($items[1]));
            $star_type->set_kelvin_min(trim($items[2]));
            $star_type->set_kelvin_max(trim($items[3]));
            $star_type->set_star_class(trim($items[4]));
            $star_type->set_part(trim($items[5]));
            $this->starTypeCommand->insertEntity($star_type);
        }
    }
    
    public function readPlanetsTypesConfig()
    {
        $this->PLANETS_TYPES = $this->settingsRepository->findSettingByKey ('PLANETS_TYPES')->getText();
        preg_match_all("/([^\[\]]+)/", $this->PLANETS_TYPES, $matches);
        if(!$matches[0]){
            throw new FailPlanetsTypesParse('PLANETS_TYPES parse error');
        }
        else{
            foreach($matches[0] as $string){
                $params = explode("|", $string);
                if(count($params) != 3){
                    throw new FailPlanetsTypesParse('PLANETS_TYPES parse error ' . $string);
                }
                else{
                    $planet_type = new PlanetType(null,null,null,null,null,null,null,null,null,null,null);
                    $planet_type->setName($params[0]);
                    $factors = explode(",", $params[1]);
                    if(count($factors) != 8){
                        throw new FailPlanetsTypesParse('PLANETS_TYPES parse error ' . $params[1]);
                    }
                    else{
                        $planet_type->setMetall(floatval($factors[0]));
                        $planet_type->setHeavyGas(floatval($factors[1]));
                        $planet_type->setOre(floatval($factors[2]));
                        $planet_type->setHydro(floatval($factors[3]));
                        $planet_type->setTitan(floatval($factors[4]));
                        $planet_type->setDarkmatter(floatval($factors[5]));
                        $planet_type->setRedmatter(floatval($factors[6]));
                        $planet_type->setAnti(floatval($factors[7]));
                        $planet_type->setProbability($params[2]);
                        $planet_type = $this->planetTypeCommand->insertEntity($planet_type);
                        $this->planets_types_arr [] = $planet_type;
                    }
                }
            }
        }
    }
    
    public function starTypization(&$star)
    {
        $min = 0;
        $max = count($this->all_stars_types_probabilities) - 1;
        $index = Rand::getInteger($min, $max);
        $type = $this->all_stars_types_probabilities [$index];
        $star->setStarType($type);
    }
}
