<?php
/*
 * File: abstractModelo.php
 * ----------
 * Abstract class for a model. Contains generic implementations of update, create,
 * obtain and delete. 
 * Each table in the database should have a matching model to make changes to it.
 * Models should only be accessed through their controllers.
 */
require_once 'datos/conexionBD.php';

abstract class Modelo{

  /*
   * Function: update
   * ----------
   * Updates the table entry whose ID is equal to the variable $id. All changes must be 
   * in the $COLUMNAS_EDITABLES columns array that is passed in.
   */
  public static function update($id, $detalles, $NOMBRE_TABLA, $ID, $COLUMNAS_EDITABLES){
    $erroresParametros = array();
    $keys=array();
    $values=array();
    //revisar que los nombres de las columnas existan y sean editables
    foreach($detalles as $key=>$value){
      if (!array_key_exists(strtoupper($key),$COLUMNAS_EDITABLES)){
        $erroresParametros[]=$key;
      }
      else{
        $keys[]=$COLUMNAS_EDITABLES[strtoupper($key)];
        $values[]=$value;
      }
    }
    //revisar que no haya ningun error en los nombres de las columnas
    if(count($erroresParametros)>0){
      throw new ExcepcionApi(400, "No existen los valores ". implode(",", $erroresParametros).".");
    }

    try{
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
      $comando = "UPDATE " . $NOMBRE_TABLA . " SET "
        . implode("=? , ", $keys)
        . "=? WHERE " . $ID ."=?";
      $sentencia = $pdo->prepare($comando);

      foreach($values as $key=>&$value){
        $sentencia->bindParam(($key+1), $value);
      }
      $sentencia->bindParam(count($keys)+1, $id);
      $resultado = $sentencia->execute();

      return $sentencia->rowCount();
    }catch(PDOException $e){
      throw new ExcepcionApi($e->getCode(), $e->getMessage());
    }
  }

  /*
   * Function: crear
   * ----------
   * Creates a new entry to the table. All details to the entry must in the $COLUMNAS_EDITABLES
   * columns array.
   */
  public static function crear($detalles, $NOMBRE_TABLA, $ID, $COLUMNAS_EDITABLES){
    $erroresParametros = array();
    $keys=array();
    $values=array();
    //revisar que los nombres de las columnas existan y sean editables
    foreach($detalles as $key=>$value){
      if (!array_key_exists(strtoupper($key),$COLUMNAS_EDITABLES)){
        $erroresParametros[]=$key;
      }
      else{
        $keys[]=$COLUMNAS_EDITABLES[strtoupper($key)];
        $values[]=$value;
      }
    }
    //revisar que no haya ningun error en los nombres de las columnas
    if(count($erroresParametros)>0){
      throw new ExcepcionApi(400, "No existen los valores ". implode(",", $erroresParametros).".");
    }

    try {
			$pdo = ConexionBD::obtenerInstancia()->obtenerBD();

			//sentencia INSERT
			$comando = "INSERT INTO " . $NOMBRE_TABLA. " ( " .
      implode(",",$keys).
			") VALUES ( ";
      for($i=0; $i<count($keys)-1;$i++){
        $comando.= "?, ";
      }
      $comando.="?)";

			$sentencia = $pdo->prepare($comando);

      foreach($values as $key=>&$value){
        $sentencia->bindParam(($key+1), $value);
      }

			$resultado = $sentencia->execute();

			return $pdo->lastInsertId();
		} catch (PDOException $e) {
			throw new ExcepcionApi($e->getCode(), $e->getMessage());
		}
  }

  /*
   * Function: obtener
   * ----------
   * Gets table entry/ies. Entries where the $columna column name variable match the $valor value variable
   * are returned.
   */
  public static function obtener($columna, $valor, $NOMBRE_TABLA, $ID, $COLUMNAS_EDITABLES){
    //revisar que la columna de busqueda sea valida
    if($columna!=$ID && !in_array($columna, $COLUMNAS_EDITABLES))
      throw new ExcepcionApi(400, "La columna (" . $columna . ") no existe en la tabla " . $NOMBRE_TABLA . ".");
    try{
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "SELECT * FROM " . $NOMBRE_TABLA .
        " WHERE ".$columna." LIKE ?";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $valor);

      $resultado = $sentencia->execute();

      if($resultado){
        $entries = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $entries;
      }else{
        return null;
      }
    } catch (PDOException $e) {
      throw new ExcepcionApi($e->getCode(), $e->getMessage());
    }
  }

  /*
   * Function: obtener
   * ----------
   * Deletes table entry/ies. Entries where the $columna column name variable match the $valor value variable
   * are deleted.
   */
  public static function borrar($columna, $valor, $NOMBRE_TABLA, $ID, $COLUMNAS_EDITABLES){
    if($columna!=$ID && !in_array($columna, $COLUMNAS_EDITABLES))
      throw new ExcepcionApi(400, "La columna (" . $columna . ") no existe en la tabla " . $NOMBRE_TABLA . ".");
    try{
      $pdo = $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "DELETE FROM " . $NOMBRE_TABLA
      . " WHERE " . $columna . " = ?";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $valor);

      $sentencia->execute();

      return $sentencia->rowCount();
    }catch(PDOException $e){
      throw new ExcepcionApi($e->getCode(), $e->getMessage());
    }
  }

  /**
   * Consigue el ID de el ultimo usuario agregado
   *
   * @return id del ultimo usuario
   */
  /*
   * Function: IDmasReciente
   * ----------
   * Gets the ID of the latest entry edited. 
   */
  public static function IDmasReciente(){
    try {
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
      return $pdo->lastInsertID();
    } catch (PDOException $e){
      throw new ExcepcionApi($e->getCode(), $e->getMessage());
    }
  }
}
