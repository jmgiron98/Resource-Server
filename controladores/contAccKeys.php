  <?php
  /*
   * File: contAccKeys.php
   * ----------
   * Controler for the access key database. Used in the authenitfication process. 
   * Not accessible or editable directly.
   */

  require_once 'modelos/modAccKeys.php';

  class accessKeys{

    //error codes 
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
   * Adds a new access key to the database. 
   */
  public static function registrar($identifier, $expDateTime, $userID, $clientID){
    if(modeloAccessKeys::crear($identifier, $expDateTime->getTimestamp(), (int)$userID, $clientID)){
      return true;
    }else{
      throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
    }
  }

    /*
     * Function: revocar
     * ----------
     * Removes an access key from the database. Called when an access key is revoked.
     */
    public static function revocar($tokenId){
      if(modeloAccessKeys::eliminar($tokenId)){
        return true;
      } else{
        throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Ha ocurrido un error");
      }
    }

    /*
     * Function: tokenEsActivo
     * ----------
     * Checks if an access key is active, that is, still in the database. 
     */
    public static function tokenEsActivo($tokenId){
      $columna = modeloAccessKeys::ID_OAUTH;
      $accessToken = modeloAccessKeys::obtener($columna, $tokenId);
      if($accessToken!=NULL){
        $estado = (int)$accessToken[0][modeloAccessKeys::ESTATUS];
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
     * Deactivates an access token by changing its status.
     */
    public static function desactivar($tokenId){
      $columna = modeloAccessKeys::ID_OAUTH;
      $nuevoEstatus = 0;
      $resultado = modeloAccessKeys::cambiarValor($tokenID, $columna, $nuevoEstatus);
      return $resultado;
    }


  }
