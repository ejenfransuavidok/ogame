<?php

namespace Entities\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Universe\Model\Planet;
use Universe\Model\EntityRepositoryInterface;
use Settings\Model\SettingsRepositoryInterface;


class SourceRepository implements EntityRepositoryInterface
{
    private $settings_ids = [
        'ELECTRICITY'       => 'Электричество',
        'METALL'            => 'Металл',
        'HEAVYGAS'          => 'Тяжелый газ',
        'ORE'               => 'Обогащенная руда',
        'HYDRO'             => 'Водород',
        'TITAN'             => 'Титан',
        'DARKMATTER'        => 'Темная материя',
        'REDMATTER'         => 'Красная материя',
        'ANTI'              => 'Антивещество'
    ];
    private $settings_prices = [
    ];
    private $settings_pictures = [
    ];
    private $data = [
    ];
    
    public function __construct(
        AdapterInterface            $db,
        HydratorInterface           $hydrator,
        Source                      $sourcePrototype,
        SettingsRepositoryInterface $settingsRepository        
    ) {
        $this->db                   = $db;
        $this->hydrator             = $hydrator;
        $this->sourcePrototype      = $sourcePrototype;
        $this->settingsRepository   = $settingsRepository;
        $this->createData();
    }
    
    public function createData()
    {
        foreach($this->settings_ids as $item => $russname){
            $this->data [$item] = 
                [
                    'id'            => $item,
                    'name'          => $russname,
                    'description'   => '',
                    'amount'        => 0,
                    'picture'       => 0,
                    'price'         => 0
                ];
            $key = sprintf('DONATE_2_%s_4_FINISHING_BUILDING', $item);
            $price = $this->settingsRepository->findSettingByKey($key)->getText();
            $this->settings_prices [$key] = $price;
            $key = sprintf('THUMB_%s', $item);
            $picture = $this->settingsRepository->findSettingByKey($key)->getText();
            $this->settings_pictures [$key] = $picture;
            $amount = 0;
            $this->data [$item] = 
                [
                    'id'            => $item,
                    'name'          => $russname,
                    'description'   => '',
                    'amount'        => $amount,
                    'picture'       => $picture,
                    'price'         => $price
                ];
        }
    }
    
    public function findAllEntities($criteria='')
    {
        return array_map(function ($source) {
            return new Source(
                $source['name'],
                $source['description'],
                $source['amount'],
                $source['picture'],
                $source['price'],
                $source['id']
            );
        }, $this->data);
    }
    
    public function findEntity($id, $criteria='')
    {
        if (! isset($this->data[$id])) {
            throw new DomainException(sprintf('Source by id "%s" not found', $id));
        }
        return new Source(
            $this->data[$id]['name'],
            $this->data[$id]['description'],
            $this->data[$id]['amount'],
            $this->data[$id]['picture'],
            $this->data[$id]['price'],
            $this->data[$id]['id']
        );
    }
    
    public function findBy($criteria, $orderBy='', $limit='', $offset='')
    {
        return $this->findAllEntities($criteria);
    }
    
    public function findOneBy($criteria, $orderBy='')
    {
        return $this->findEntity(0, $criteria);
    }
    
}
