<?php
namespace Settings;

class Module
{
    /*
     * чтобы организовать роутинг, нужно ServiceManager - у
     * загрузить конфигурацию, для этого используем метод
     * getConfig (), чтобы весь огромный конфиг не грузить в
     * одной функции, создадим специальный файл конфигов
     * /config/module.config.php
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
