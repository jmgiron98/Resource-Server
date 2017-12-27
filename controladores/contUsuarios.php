<?php

require_once "modelos/modUsuarios.php";
require_once "controladores/abstractControlador.php";

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class usuarios extends Controlador implements controladorInterface
{

  const MODELO = 'modeloUsuarios';

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
   * Function: obtenerUsuarioPorID
   * -------
   * Finds a user by its id. Used in authentification.
   * Note: same functionality is provided by the obtener funciton, but it avoids
   * creating the request and response objects.
   */
  public static function obtenerUsuarioPorID($idUsuario){
    $columna = modeloUsuarios::ID;
    $usuario = modeloUsuarios::obtener($columna, $idUsuario);
    return $usuario[0];

  }

  /*
   * Function: loguear
   * -------
   * Checks a user's username and password match. Used in authentification.
   */
  public static function loguear($username, $password){

    return modeloUsuarios::autenticar($username, $password);

  }



}
