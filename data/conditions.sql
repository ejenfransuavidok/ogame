CREATE TABLE `conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(1, 'speed', 'Cкорость объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(2, 'capacity', 'Грузоподъемность объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(3, 'fuel_consumption', 'Удельное потребление топлива объектом');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(4, 'fuel_tank_size', 'Объем топливных баков объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(5, 'attak_power', 'Сила атаки объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(6, 'rate_of_fire', 'Скорострельность объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(7, 'the_number_of_attak_targets', 'Количество одновременных целей для объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(8, 'sheep_size', 'Размер корабля');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(9, 'protection', 'Защита объекта');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(10, 'number_of_guns', 'Количество орудий');
INSERT INTO `conditions` (`id`, `name`, `description`) VALUES
(11, 'construction_time', 'Время постройки');
