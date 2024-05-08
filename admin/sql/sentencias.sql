CREATE TABLE `reposteria`.`usuario` (`id` INT NOT NULL AUTO_INCREMENT , `nombres` VARCHAR(255) NOT NULL , `apellidoP` VARCHAR(255) NOT NULL , `apellidoM` VARCHAR(255) NOT NULL , `edad` INT(3) NOT NULL , `email` VARCHAR(255) NOT NULL , `pass` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `reposteria`.`producto` (`id` INT NOT NULL AUTO_INCREMENT , `nombre` VARCHAR(255) NOT NULL , `descripcion` TEXT NOT NULL , `existencia` INT(3) NOT NULL , `precio` INT NOT NULL , `fotoURL` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
