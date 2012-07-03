SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `maquinas` ;
CREATE SCHEMA IF NOT EXISTS `maquinas` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `maquinas` ;

-- -----------------------------------------------------
-- Table `maquinas`.`lugar`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`lugar` (
  `idlugar` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de lugar' ,
  `nombre` VARCHAR(45) NOT NULL COMMENT 'Nombre del lugar' ,
  PRIMARY KEY (`idlugar`) )
ENGINE = InnoDB
COMMENT = 'Lugares donde se alquila';


-- -----------------------------------------------------
-- Table `maquinas`.`cliente`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`cliente` (
  `idcliente` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del cliente' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombres' ,
  `apaterno` VARCHAR(45) NOT NULL COMMENT 'Apellido Paterno' ,
  `amaterno` VARCHAR(45) NOT NULL COMMENT 'Apellido Materno' ,
  `dni` VARCHAR(11) NULL COMMENT 'Documento de identidad' ,
  PRIMARY KEY (`idcliente`) )
ENGINE = InnoDB
COMMENT = 'Clientes de las maquinas';


-- -----------------------------------------------------
-- Table `maquinas`.`admin`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`admin` (
  `idadmin` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del administrador' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombre completo del administrador' ,
  `usuario` VARCHAR(45) NOT NULL COMMENT 'Nombre de usuario' ,
  `password` VARCHAR(32) NOT NULL COMMENT 'Contraseña' ,
  PRIMARY KEY (`idadmin`) ,
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC) )
ENGINE = InnoDB
COMMENT = 'Administradores del sistema\n';


-- -----------------------------------------------------
-- Table `maquinas`.`operador`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`operador` (
  `idoperador` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del operador' ,
  `nombres` VARCHAR(45) NOT NULL COMMENT 'Nombres completos del operador' ,
  PRIMARY KEY (`idoperador`) )
ENGINE = InnoDB
COMMENT = 'Los que operan las máquinas';


-- -----------------------------------------------------
-- Table `maquinas`.`maquina`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`maquina` (
  `idmaquina` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la máquina' ,
  `descripcion` VARCHAR(45) NOT NULL COMMENT 'Descripción de la máquina' ,
  `estado` ENUM('a','m') NOT NULL DEFAULT 'a' COMMENT 'Estado actual de la máquina. a indica activo y m mantenimiento' ,
  `idoperador` INT NOT NULL COMMENT 'Referencia de operador' ,
  PRIMARY KEY (`idmaquina`) ,
  INDEX `fk_maquina_operador1` (`idoperador` ASC) ,
  CONSTRAINT `fk_maquina_operador1`
    FOREIGN KEY (`idoperador` )
    REFERENCES `maquinas`.`operador` (`idoperador` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Las máquinas que se alquilan';


-- -----------------------------------------------------
-- Table `maquinas`.`mantenimiento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`mantenimiento` (
  `idmantenimiento` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del manteminiento' ,
  `idmaquina` INT NOT NULL COMMENT 'Identificador del mantemiento' ,
  `fecha` TIMESTAMP NOT NULL COMMENT 'Fecha del último mantenimiento' ,
  PRIMARY KEY (`idmantenimiento`, `idmaquina`) ,
  INDEX `fk_mantenimiento_maquina1` (`idmaquina` ASC) ,
  CONSTRAINT `fk_mantenimiento_maquina1`
    FOREIGN KEY (`idmaquina` )
    REFERENCES `maquinas`.`maquina` (`idmaquina` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Registro de mantenimientos';


-- -----------------------------------------------------
-- Table `maquinas`.`combustible`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`combustible` (
  `idcombustible` INT NOT NULL AUTO_INCREMENT COMMENT 'Identificador del recibo' ,
  `cantidad` FLOAT NOT NULL COMMENT 'Cantidad de combustible en galones' ,
  PRIMARY KEY (`idcombustible`) )
ENGINE = InnoDB
COMMENT = 'Vales de combustible\n';


-- -----------------------------------------------------
-- Table `maquinas`.`alquiler`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `maquinas`.`alquiler` (
  `idalquiler` INT NOT NULL AUTO_INCREMENT COMMENT 'Numero de alquiler' ,
  `idcliente` INT NOT NULL COMMENT 'Identificador del cliente' ,
  `idmaquina` INT NOT NULL COMMENT 'Identificador de la máquina' ,
  `idlugar` INT NOT NULL ,
  `idcombustible` INT NOT NULL COMMENT 'Vale de combustible' ,
  `minutos` INT NOT NULL COMMENT 'Tiempo de alquiler en minutos' ,
  `fecha` TIMESTAMP NOT NULL COMMENT 'Fecha y hora del alquiler' ,
  `terminado` TINYINT NOT NULL DEFAULT 0 COMMENT 'Indica si el alquiler ha terminado' ,
  PRIMARY KEY (`idalquiler`) ,
  INDEX `fk_alquiler_cliente1` (`idcliente` ASC) ,
  INDEX `fk_alquiler_maquina1` (`idmaquina` ASC) ,
  INDEX `fk_alquiler_combustible1` (`idcombustible` ASC) ,
  INDEX `fk_alquiler_lugar1` (`idlugar` ASC) ,
  CONSTRAINT `fk_alquiler_cliente1`
    FOREIGN KEY (`idcliente` )
    REFERENCES `maquinas`.`cliente` (`idcliente` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquiler_maquina1`
    FOREIGN KEY (`idmaquina` )
    REFERENCES `maquinas`.`maquina` (`idmaquina` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquiler_combustible1`
    FOREIGN KEY (`idcombustible` )
    REFERENCES `maquinas`.`combustible` (`idcombustible` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alquiler_lugar1`
    FOREIGN KEY (`idlugar` )
    REFERENCES `maquinas`.`lugar` (`idlugar` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Registro de alquileres';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
