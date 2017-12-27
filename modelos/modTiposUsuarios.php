<?php
/*
 * File: modTiposUsuarios.php
 * ----------
 * Model that accesses the table containing user types. User types are also expected to hold the 
 * permission level for each available resource.
 */

require_once 'datos/conexionBD.php';

require_once 'modelos/abstractModelo.php';

class modeloTiposUsuarios extends Modelo
{
  //Constantes de la tabla
  //table name
  const NOMBRE_TABLA = "user_type_table_name";
  // name of the column holding the id
  const ID = "id";

  // names for the columns that can be edited
  private static $COLUMNAS_EDITABLES = array(
    "INSTITUCION"=>'institucion', //insitution the user type belongs to.
    "NOMBRE"=>'nombre',
    "USUARIOS"=>'usuarios',
    "TIPOS_USUARIOS"=>'tiposUsuarios'
  );

  public static function crear($datos){
    return parent::crear($datos, self::NOMBRE_TABLA, self::ID, self::$COLUMNAS_EDITABLES);
  }


  public static function update($id, $detalles){
    return parent::update($id, $detalles, self::NOMBRE_TABLA, self::ID, self::$COLUMNAS_EDITABLES);
  }

  public static function obtener($columna, $valor){
    return parent::obtener($columna, $valor, self::NOMBRE_TABLA, self::ID, self::$COLUMNAS_EDITABLES);
  }

  public static function borrar($columna, $valor){
    return parent::borrar($columna, $valor, self::NOMBRE_TABLA, self::ID, self::$COLUMNAS_EDITABLES);
  }

  /**
   * Consigue el ID de el ultimo usuario agregado
   * Get the ID of the last user added
   *
   * @return id del ultimo usuario
   */
  public static function IDmasReciente(){
    try {
      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
      return $pdo->lastInsertID();
    } catch (PDOException $e){
      throw new ExcepcionApi(500, $e->getMessage());
    }
  }

  /*
   * Function: obtenerPermisos
   * ----------
   * Gets the permissions for the user type with ID $idTipo.
   */
  public static function obtenerPermisos($idTipo)
  {
    try {

      $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

      $comando = "SELECT ";
      $comando .= implode(",", recursos::$recursos_existentes);
      $comando .= " FROM " . self::NOMBRE_TABLA
      . " WHERE " . self::ID . "=?";

      $sentencia = $pdo->prepare($comando);

      $sentencia->bindParam(1, $idTipo);

      $resultado = $sentencia->execute();

      if ($resultado){
        return $sentencia->fetch(PDO::FETCH_ASSOC);
      }
      throw new ExcepcionApi(404, "Recurso no encontrado");

    } catch (PDOException $e){
      throw new ExcepcionApi(500, $e->getMessage());
    }
  }

}
