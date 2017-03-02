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
use Universe\Model\GalaxyCommand;
use Universe\Model\PlanetSystemCommand;
use Universe\Model\StarCommand;
use Universe\Model\PlanetCommand;
use Universe\Model\SputnikCommand;
use Settings\Model\Setting;
use Settings\Model\SettingsRepositoryInterface;
use Zend\Math\Rand;


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
     * @ lock-file
     * 
     */
    private $lock;
    
    public function __construct(
        SettingsRepositoryInterface $settingsRepository, 
        GalaxyCommand $galaxyCommand,
        PlanetSystemCommand $planetSystemCommand,
        StarCommand $starCommand,
        PlanetCommand $planetCommand,
        SputnikCommand $sputnikCommand)
    {
        $this->settingsRepository   = $settingsRepository;
        $this->galaxyCommand        = $galaxyCommand;
        $this->planetSystemCommand  = $planetSystemCommand;
        $this->starCommand          = $starCommand;
        $this->planetCommand        = $planetCommand;
        $this->sputnikCommand       = $sputnikCommand;
        $this->logpath = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/logs/universe-generation.log";
        $this->stream = @fopen($this->logpath, 'w+', false);
        if (! $this->stream) {
            throw new \Exception('Failed to open stream ' . $this->logpath);
        }
        $this->writer = new Stream($this->stream);
        $this->logger = new Logger();
        $this->logger->addWriter($this->writer);
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
        $this->GalaxiesIterator();
        
        /**
         * 
         * @удалим lock файл
         * 
         */
        unlink($this->lock);
        
        $result = new JsonModel(array(
            'messages'  => 'start',
            'success'   =>true,
        ));
        return $result;
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
            $galaxy = new Galaxy('', '', '', '', '');
            
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
            $planet_system = new PlanetSystem('','','','','','');
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
            $star = new Star('','','','','','','');
            
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
            
        }
        return $this->PLANET_SYSTEMS_COUNT * $this->PLANET_SYSTEM_SIZE;
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
            
            $planet = new Planet('','','','','','','');
            
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
                $sputnik = new Sputnik('','','','','','','');
                
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
                $sputnik->SetParentPlanet($parent_planet);
                
                /**
                 * 
                 * @определим родительскую планетную систему
                 * 
                 */
                $sputnik->setCelestialParent($parent);
                
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
}
