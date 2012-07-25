SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `maquinas` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `maquinas` ;

-- -----------------------------------------------------
-- Table `maquinas`.`lugar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`lugar` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`lugar` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de lugar' ,
  `nombre` VARCHAR(45) NOT NULL COMMENT 'Nombre del lugar' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Lugares donde se alquila';


-- -----------------------------------------------------
-- Table `maquinas`.`cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`cliente` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`cliente` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del cliente' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombres' ,
  `apaterno` VARCHAR(45) NOT NULL COMMENT 'Apellido Paterno' ,
  `amaterno` VARCHAR(45) NOT NULL COMMENT 'Apellido Materno' ,
  `dni` VARCHAR(11) NULL COMMENT 'Documento de identidad' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Clientes de las maquinas';


-- -----------------------------------------------------
-- Table `maquinas`.`admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`admin` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`admin` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del administrador' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombre completo del administrador' ,
  `usuario` VARCHAR(45) NOT NULL COMMENT 'Nombre de usuario' ,
  `password` VARCHAR(32) NOT NULL COMMENT 'Contraseña' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC) )
ENGINE = InnoDB
COMMENT = 'Administradores del sistema\n';


-- -----------------------------------------------------
-- Table `maquinas`.`operador`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`operador` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`operador` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del operador' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombres completos del operador' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Los que operan las máquinas';


-- -----------------------------------------------------
-- Table `maquinas`.`maquina`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`maquina` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`maquina` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la máquina' ,
  `descripcion` VARCHAR(45) NOT NULL COMMENT 'Descripción de la máquina' ,
  `estado` ENUM('a','m') NOT NULL DEFAULT 'a' COMMENT 'Estado actual de la máquina. a indica activo y m mantenimiento' ,
  `idoperador` INT NOT NULL COMMENT 'Referencia de operador' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_maquina_operador1` (`idoperador` ASC) ,
  CONSTRAINT `fk_maquina_operador1`
    FOREIGN KEY (`idoperador` )
    REFERENCES `maquinas`.`operador` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Las máquinas que se alquilan';


-- -----------------------------------------------------
-- Table `maquinas`.`mantenimiento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`mantenimiento` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`mantenimiento` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del manteminiento' ,
  `idmaquina` INT NOT NULL COMMENT 'Identificador del mantemiento' ,
  `fecha` TIMESTAMP NOT NULL COMMENT 'Fecha del último mantenimiento' ,
  PRIMARY KEY (`id`, `idmaquina`) ,
  INDEX `fk_mantenimiento_maquina1` (`idmaquina` ASC) ,
  CONSTRAINT `fk_mantenimiento_maquina1`
    FOREIGN KEY (`idmaquina` )
    REFERENCES `maquinas`.`maquina` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Registro de mantenimientos';


-- -----------------------------------------------------
-- Table `maquinas`.`combustible`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`combustible` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`combustible` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del recibo' ,
  `cantidad` FLOAT NOT NULL COMMENT 'Cantidad de combustible en galones' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Vales de combustible\n';


-- -----------------------------------------------------
-- Table `maquinas`.`alquiler`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`alquiler` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`alquiler` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Numero de alquiler' ,
  `idcliente` INT NOT NULL COMMENT 'Identificador del cliente' ,
  `idmaquina` INT NOT NULL COMMENT 'Identificador de la máquina' ,
  `idlugar` INT NOT NULL ,
  `recibo` VARCHAR(45) NOT NULL COMMENT 'Recibo de pago en caja' ,
  `minutos` INT NOT NULL COMMENT 'Tiempo de alquiler en minutos' ,
  `fecha` TIMESTAMP NOT NULL DEFAULT '2012-07-23 00:00:00' COMMENT 'Fecha y hora del alquiler' ,
  `terminado` TINYINT NOT NULL DEFAULT 0 COMMENT 'Indica si el alquiler ha terminado' ,
  `observaciones` TEXT NOT NULL COMMENT 'Observaciones o apuntes' ,
  `combustiblenro` INT NOT NULL COMMENT 'Vale de combustible' ,
  `combustiblecan` FLOAT NOT NULL COMMENT 'Cantidad de combustible' ,
  `anulado` TINYINT NOT NULL DEFAULT 0 ,
  `creado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_alquiler_cliente1` (`idcliente` ASC) ,
  INDEX `fk_alquiler_maquina1` (`idmaquina` ASC) ,
  INDEX `fk_alquiler_lugar1` (`idlugar` ASC) ,
  CONSTRAINT `fk_alquiler_cliente1`
    FOREIGN KEY (`idcliente` )
    REFERENCES `maquinas`.`cliente` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquiler_maquina1`
    FOREIGN KEY (`idmaquina` )
    REFERENCES `maquinas`.`maquina` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquiler_lugar1`
    FOREIGN KEY (`idlugar` )
    REFERENCES `maquinas`.`lugar` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Registro de alquileres';


-- -----------------------------------------------------
-- Table `maquinas`.`opciones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `maquinas`.`opciones` ;

CREATE  TABLE IF NOT EXISTS `maquinas`.`opciones` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Id de la opción' ,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre de la opción' ,
  `descripcion` TEXT NOT NULL COMMENT 'Descripción de la opción' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Opciones del sistema.';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
