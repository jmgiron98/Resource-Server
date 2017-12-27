<?php

require_once "modelos/modTiposUsuarios.php";
require_once "controladores/contUsuarios.php";
require_once "controladores/abstractControlador.php";

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class tiposUsuarios extends Controlador implements controladorInterface
{
  const MODELO = 'modeloTiposUsuarios';

  public static function post(ServerRequestInterface &$request, ResponseInterface $response){
    return self::crear($request, $response);
  }


  public static function get(ServerRequestInterface &$request, ResponseInterface $response){
    return self::obtener($request, $response);
  }

  public static function put(ServerRequestInterface &$request, ResponseInterface $response){
    return self::update($request, $response);
  }

  public static function delete(ServerRequestInterface &$request, ResponseInterface $response){
    return self::borrar($request, $response);
  }

  //Funciones implementadas en el controlador abstracto
  protected static function crear(&$request, $response)
  {
    return parent::crear($request, $response, self::MODELO);
  }

  protected static function obtener(&$request, $response)
  {
    return parent::obtener($request, $response, self::MODELO);
  }

  protected static function borrar(&$request, $response)
  {
    return parent::borrar($request, $response, self::MODELO);
  }

  protected static function update(&$request, $response)
  {
    return parent::update($request, $response, self::MODELO);
  }

  /* 
   * Function: obtenerPermisosPorTipo
   * ---------
   * Finds the permissions for a given user type.
   */
  public static function obtenerPermisosPorTipo($idTipo){
    return modeloTiposUsuarios::obtenerPermisos($idTipo); 
  }

  /* 
   * Function: obtenerPermisosPorUsuario
   * ---------
   * Gets the permissions for a given user. Uses the user controller.
   */
  public static function obtenerPermisosPorUsuario($idUsuario){
    $usuario = usuarios::obtenerUsuarioPorID($idUsuario);
    $idTipo = $usuario["tipo_usuario"];
    return self::obtenerPermisosPorTipo($idTipo);
  }

}
