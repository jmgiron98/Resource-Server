<?php

require_once 'datos/conexionBD.php';

class modeloAccessKeys
{
  //table name
  const NOMBRE_TABLA = "access_token_table_name";
  //column names
  const ID_INTERNO = "id";
  const ID_OAUTH = "acc_token_identifier";
  const FECHA_EXP = "expiration_date";
  const ID_USUARIO = "user_id";
  const CLIENT = "client";
  const ESTATUS = "status";

  //error codes
  const ESTADO_CREACION_EXITOSA = 1;
  const ESTADO_CREACION_FALLIDA = 2;
  const ESTADO_ERROR_BD = 3;
  const ESTADO_AUSENCIA_CLAVE_API = 4;
  const ESTADO_CLAVE_NO_AUTORIZADA = 5;
  const ESTADO_URL_INCORRECTA = 6;
  const ESTADO_FALLA_DESCONOCIDA = 7;
  const ESTADO_PARAMETROS_INCORRECTOS = 8;

  /*
   * Function: crear
   * ----------
   * Adds an access key to the access key table.
   */
  public static function crear($identifier, $expDateTime, $userID, $clientID){
    try{
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
      self::ID_OAUTH . "," .
      self::FECHA_EXP . "," .
      self::ID_USUARIO . "," .
      self::CLIENT . ")" .
      " VALUES (?,?,?,?)";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $identifier, PDO::PARAM_STR);
      $sentencia->bindParam(2, $expDateTime, PDO::PARAM_INT);
      $sentencia->bindParam(3, $userID, PDO::PARAM_INT);
      $sentencia->bindParam(4, $clientID, PDO::PARAM_STR);

      $resultado = $sentencia->execute();

      return $resultado;
    }catch (PDOException $e) {
			throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
		}
  }

  /*
   * Function: eliminar
   * ----------
   * Eliminates an access token the the token ID $tokenId from the table.
   */
  public static function eliminar($tokenId){
    try{
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "DELETE FROM " . self::NOMBRE_TABLA .
        " WHERE " . self::ID_OAUTH ."=?";

      $sentencia = $pdo->prepare($comando);
      $sentencia->bindParam(1, $tokenId);

      $resultado = $sentencia->execute();
      return $resultado;
    }catch (PDOException $e) {
			throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
		}
  }

  /*
   * Function: obtener
   * ----------
   * Gets access token/s whose $columna column value matches the $valor variable.
   */
  public static function obtener($columna, $valor){
    try{
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
        " WHERE " . $columna . "=?";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $valor);

      $resultado = $sentencia->execute();
      if($resultado){
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
      }else {
        return null;
      }
    }catch (PDOException $e) {
      throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
    }
  }

  /*
   * Function: cambiarValor
   * ----------
   * Updates an access token entry. Changes the entry with ID $tokenID's column $columna to $valor. 
   */  
  public static function cambiarValor($tokenID, $columna, $valor){
    try{

      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "UPDATE " . self::NOMBRE_TABLA .
        " SET ? = ? WHERE "
        . self::ID_OAUTH . "=?";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $columna);
      $sentencia->bindParam(2, $valor);
      $sentencia->bindParam(3, $tokenID);

      $resultado = $sentencia->execute();

      if($resultado){
        return true;
      } else{
        return false;
      }
    }catch(PDOException $e){
      throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
    }
  }

}
