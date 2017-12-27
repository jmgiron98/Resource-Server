<?php
/* File: abstractControlador.php
 * ----------
 * Controller abstract. Performs generic operations for get, create, update, 
 * and delete operations for various resources. Requires a $modelo variable to 
 * be passed into the functions which has the name of the corresponding model class.
 * The purpose of the controller is to unpack the request, call the model to make the
 * desired action to the database, and prepare the response.
 */
abstract class Controlador{

  /*
   * Llama al modelo para registrar un nuevo usuario.
   */
  /*
   * Function: crear
   * ----------
   * Calls on the model to create a new obect. Details for the object are expected to be
   * in the request body. 
   */
  protected static function crear(&$request, $response, $modelo)
  {
    $detalles = $request->getParsedBody();

    $resultado = call_user_func([$modelo, 'crear'], $detalles);

    if($resultado) {
        $response = $response->withStatus(201);
        $response->getBody()->write(json_encode(
          [
            "estado" => 201,
            "mensaje" => utf8_encode("Registro con exito!"),
            "id" => $resultado
          ]));
        return $response;
    }else{
      throw new ExcepcionApi(400, "Ha ocurrido un error");
    }
  }

  /*
   * Consigue los ususarios de la institucion del usuario.
   */
  /*
   * Function: obtener
   * ----------
   * Gets the information from a GET request. If an ID is specified, the specific entry in the resource table with that
   * ID is returned. Otherwise, all the entries from the logged user's institution (institucion) are returned.
   * Note: the database was designed so that mutliple institutions could be in the same table, so there is a column
   * called institution in every resource that specifies which insititution that entry belongs to.
   */
  protected static function obtener(&$request, $response, $modelo)
  {
    $pathInfo = $request->getQueryParams()["PATH_INFO"];
    $elementos=array();
    if(count($pathInfo)==1){
      $elementos = call_user_func([$modelo, 'obtener'], 'institucion', $request->getAttribute('usuario')['institucion']);
    }else{
      $elementos = call_user_func([$modelo, 'obtener'], $modelo::ID, (int)$pathInfo[1]);
    }
    if(empty($elementos)){
      $response=$response->withStatus(204);
    }
    $response->getBody()->write(json_encode(["Resultados"=>$elementos]));
    return $response;
  }

  /*
   * Function: borrar
   * ----------
   * Calls on the model to delete an object. The object's id must be specified.
   */
  protected static function borrar(&$request, $response, $modelo)
  {
    $pathInfo = $request->getQueryParams()["PATH_INFO"];
    if(count($pathInfo)!=2){
      throw new ExcepcionApi(400, "Debe especificar el usuario a borrar.");
    }
    $resultado = $modelo::borrar('id', $pathInfo[1]);
    if ($resultado>0){
      $response = $response->withStatus(200);
      if($resultado == 1)
        $response->getBody()->write(json_encode(["Mensaje"=>$resultado . " elemento fue eliminado exitosamente."]));
      else
        $response->getBody()->write(json_encode(["Mensaje"=>$resultado . " elementos fueron eliminados exitosamente."]));
    }else{
      $response = $response->withStatus(404);
      $response->getBody()->write(json_encode(["Mensaje"=>"No se encuentra el elemento especificado."]));
    }
    return $response;
  }

  /*
   * Function: update
   * ----------
   * Calls on the model to update an object. The object's id must be specified.
   */
  protected static function update(&$request, $response, $modelo)
  {
    $pathInfo = $request->getQueryParams()["PATH_INFO"];
    if(count($pathInfo)!=2){
      throw new ExcepcionApi(400, "Debe especificar el usuario a actualizar.");
    }
    $cambios = $request->getParsedBody();
    $id = (int)$request->getQueryParams()["PATH_INFO"][1];

    if(!$cambios || count($cambios)==0){
      $response = $response->withStatus(400);
      $response->getBody()->write(json_encode(["Mensaje"=>"Se deben especificarlos valores a cambiar"]));
      return $response;
    }

    $resultado = $modelo::update($id, $cambios);
    if ($resultado>0){
      $response = $response->withStatus(200);
      if($resultado == 1)
        $response->getBody()->write(json_encode(["Mensaje"=>$resultado . " elemento fue actualizado exitosamente."]));
      else
        $response->getBody()->write(json_encode(["Mensaje"=>$resultado . " elementos fueron actualizados exitosamente."]));
    }else{
      $response = $response->withStatus(404);
      $response->getBody()->write(json_encode(["Error"=>"No se pudo realizar ningun cambio."]));
    }
    return $response;
  }
}
