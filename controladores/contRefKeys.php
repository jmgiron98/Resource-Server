<?php
  /*
   * File: contRefKeys.php
   * ----------
   * Controler for the refresh key database. Used in the authenitfication process. 
   * Not accessible or editable directly.
   */

require_once 'modelos/modRefKeys.php';

class refreshKeys{

  const ESTADO_CREACION_EXITOSA = 1;
  const ESTADO_CREACION_FALLIDA = 2;
  const ESTADO_ERROR_BD = 3;
  const ESTADO_AUSENCIA_CLAVE_API = 4;
  const ESTADO_CLAVE_NO_AUTORIZADA = 5;
  const ESTADO_URL_INCORRECTA = 6;
  const ESTADO_FALLA_DESCONOCIDA = 7;
  const ESTADO_PARAMETROS_INCORRECTOS = 8;
  const ESTADO_RECURSO_NO_EXISTE = 9;
  /*
   * Function: registrar
   * ----------
   * Adds a new refresh key to the database. 
   */
  public static function registrar($identifier, $expDateTime, $accTokenIdentifier){
    if(modeloRefreshKeys::crear($identifier, $expDateTime->getTimestamp(), $accTokenIdentifier)){
      return true;
    } else{
      throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
    }
  }
  /*
   * Function: revocar
   * ----------
   * Removes a refresh key from the database. Called when an access key is revoked.
   */
  public static function revocar($tokenId){
    if(modeloRefreshKeys::eliminar($tokenId)){
      return;
    } else{
      throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Ha ocurrido un error");
    }
  }

  /*
   * Function: tokenEsActivo
   * ----------
   * Checks if a refresh key is active, that is, still in the database. 
   */
  public static function tokenEsActivo($tokenId){
    $columna = modeloRefreshKeys::ID_OAUTH;
    $refreshToken = modeloRefreshKeys::obtener($columna, $tokenId);
    if($refreshToken!=NULL && !empty($refreshToken)){
      $estado = $refreshToken[0][modeloRefreshKeys::ESTATUS];
      if($estado){
        return true;//token activo
      } else {
        return false;//token desactivado
      }
    } else{
      return false; //Token no encontrado(token no esta activo)
    }
  }

  /*
   * Function: desactivar
   * ----------
   * Deactivates a refresh token by changing its status.
   */
  public static function desactivar($tokenId){
    $columna = modeloRefreshKeys::ID_OAUTH;
    $nuevoEstatus = 0;
    $resultado = modeloRefreshKeys::cambiarValor($tokenID, $columna, $nuevoEstatus);
    return $resultado;
  }


}
