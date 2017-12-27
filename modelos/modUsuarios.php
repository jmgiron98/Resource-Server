<?php
/*
 * File: modUsuarios.php
 * ---------
 * Model that accesses the users table. 
 */

require_once 'datos/conexionBD.php';

require_once 'modelos/abstractModelo.php';

class modeloUsuarios extends Modelo
{
  const NOMBRE_TABLA="user_type_table_name";
  const ID='id';

  private static $COLUMNAS_EDITABLES = array(
    'NICKNAME' => 'nickname',
    'EMAIL' => 'email',
    'PASSWORD' => 'password',
    'TIPO_USUARIO' => 'tipo_usuario',
    'INSTITUCION' => 'institucion', // institution the user belongs to
  );

  public static function crear($datosUsuario){
    $datosUsuario['password'] = self::encriptarContrasena($datosUsuario['password']);
    return parent::crear($datosUsuario, self::NOMBRE_TABLA, self::ID, self::$COLUMNAS_EDITABLES);
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

  /*
   * Function: Autenticar
   * ------------
   * Verifies that a given user's password matches it's provided password. The user can be looked up by 
   * nickname(username) or email address. $correo should hold the user's username or email and $contrasena 
   * the provided password. If successful, the function will return the user's id if the password was correct
   * or null if it wasn't. If the username/email was incorrect, null will also be returned.
   */ 
  public static function autenticar($correo, $contrasena){
    $comando = "SELECT ".self::$COLUMNAS_EDITABLES['PASSWORD'].",". self::ID." FROM " . self::NOMBRE_TABLA .
			" WHERE " . self::$COLUMNAS_EDITABLES['EMAIL'] . "=? OR " . self::$COLUMNAS_EDITABLES['NICKNAME'] . "=?";

		try {

			$sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

			$sentencia->bindParam(1, $correo);

      $sentencia->bindParam(2, $correo);

			$sentencia->execute();

			if ($sentencia) {
				$resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

				if (self::validarContrasena($contrasena, $resultado['password'])){
					return $resultado['id'];
				} else return null;
			} else {
				return null;
			}
		} catch (PDOException $e) {
			throw new ExcepcionApi($e->getCode(), $e->getMessage());
		}
  }

  /*
   * Function: encriptarContrasena
   * -----------
   * Function wrapper for password_hash
   * @return password's hash.
   */
  private function encriptarContrasena($contrasenaPlana)
  {
    if($contrasenaPlana)
      return password_hash($contrasenaPlana, PASSWORD_DEFAULT);
    else return null;
  }

  /*
   * Function: validarContrasena
   * ------------
   * Function wrapper for password_verify
   */
  private function validarContrasena($contrasenaPlana, $contrasenaHash)
  {
    return password_verify($contrasenaPlana, $contrasenaHash);
  }


}
