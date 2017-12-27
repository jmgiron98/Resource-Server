<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface controladorInterface
{
  /*
   * Metodo post:
   * Responde a post requests, crea un nuevo elemento del recurso.
   *
   * @param ServerRequestInterface    $request (no deberia ser necesario editarlo)
   * @param ResponseInterface         $response
   *
   * @return ResponseInterface (Contiene la informacion del elemento).
   */
  public static function post(ServerRequestInterface &$request, ResponseInterface $response);

  /*
   * Metodo get:
   * Consigue a todos los elementos, o si se especifica un id, al elemento con ese id.
   *
   * @param ServerRequestInterface    $request (no deberia ser necesario editarlo)
   * @param ResponseInterface         $response
   *
   * @return ResponseInterface (Mensaje de exito o fracaso)
   */
  public static function get(ServerRequestInterface &$request, ResponseInterface $response);

  /*
   * Metodo put:
   * Actualiza los campos de un elemento. Se debe proveer el id del recurso a actualizar
   *
   * @param ServerRequestInterface    $request (no deberia ser necesario editarlo)
   * @param ResponseInterface         $response
   *
   * @return ResponseInterface (Mensaje de exito o fracaso)
   */
  public static function put(ServerRequestInterface &$request, ResponseInterface $response);

  /*
   * Metodo delete:
   * Elimina un elemento. Se debe de haber proveido el id del recurso a eliminar.
   *
   * @param ServerRequestInterface    $request (no deberia ser necesario editarlo)
   * @param ResponseInterface         $response
   *
   * @return ResponseInterface (Mensaje de exito o fracaso)
   */
  public static function delete(ServerRequestInterface &$request, ResponseInterface $response);
}
