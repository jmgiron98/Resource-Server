<?php
/* 
 * File: router.php
 * ---------
 * Contains the router class.
 * Reads in a request and passes it to the appropiate resource 
 */
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Stream;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{

  const PERMISOS = 'permisos';
  // Premission level
  private $nivelPermiso = array(
    'get'=>1,
    'post'=> 2,
    'put'=>3,
    'delete'=>4
  );

  // Methods whose request body should be decoded
  private $requestMethodsToDecode = array(
    'post',
    'put'
  );
  /*
   * Function: __invoke
   * ---------
   * Reads in a request, verifies if the request is asking for a valid resource and the user has the permission for the
   * requested action and passes on the request to the appropriate resource controller.
   */
  public function __invoke($request, $response){

    // read in request
    $request = self::descifrarRequest($request);

    //read in the desired resource
    $recurso = $request->getQueryParams()['PATH_INFO'][0];

    //Comprobar si existe el recurso
    // Check if the resource exists
    if(!in_array($recurso, recursos::$recursos_existentes)){
      throw new ExcepcionApi(400, "No se reconoce el recurso al que intentas acceder.");
    }

    // conseguir metodo (GET,POST, PUT, DELETE);
    // get the request method
    $metodo = strtolower($request->getServerParams()['REQUEST_METHOD']);

    // user permission
    $permiso = $request->getAttribute(self::PERMISOS)[$recurso];

    if(method_exists($recurso, $metodo)){ 
      if($permiso >= $this->nivelPermiso[$metodo]){ //checks if the user had the appropriate permission level
        $respuesta = call_user_func(array($recurso, $metodo), $request, $response); //calls the appropriate resource method
        return $respuesta;
      }else{
        // error returned if user doesn't have the necessary permissions
        throw new ExcepcionApi(401, "Usuario no tiene permiso para realizar esta operacion: ". $recurso . " " . $metodo);
      }
    }else{
      // error returned if resource doesn't support the request method.
      throw new ExcepcionApi(405, utf8_encode("Método (".$metodo.") no permitido para el recurso (".$recurso.")."));
    }
  }

  /*
   * Function: descifrarPermisos
   * ---------
   * Gets the permissions from the request object and sets them as an array.
   */
  private function descifrarPermisos($request){

    $scopes= $request->getAttribute('oauth_scopes');
    $permissions = array();
    foreach($scopes as $permission){
      $scope = explode('/',$permission);
      $permissions[$scope[0]] = (int)$scope[1];
    }
    return $permissions;

  }

  /*
   * Function: descifrarRequestPath
   * ---------
   * Gets the query parameters from the request object and makes them into an array.
   */
  private function descifrarRequestPath($request){
    $queryParams = $request->getQueryParams();
    $path = $queryParams['PATH_INFO'];
    $pathArray = explode('/', $path);
    $pathArray = array_filter($pathArray);
    $queryParams['PATH_INFO'] = $pathArray;
    return $queryParams;
  }

  /*
   * Function: descifrarCuerpo
   * ---------
   * Decodes the body of the request if necessary.
   */
  private function descifrarCuerpo($request){
    $contentType= $request->getHeader('content-type')[0];
    $requestMethod = strtolower($request->getServerParams()['REQUEST_METHOD']);
    if($contentType == 'application/json' && in_array($requestMethod,$this->requestMethodsToDecode)){
      $body = $request->getBody();
      $decodedBody = json_decode($body->getContents(), true);
      return $decodedBody;
    }
    return;
  }

  /*
   * Function: descrifrarRequest
   * ---------
   * Function reads in a request object and sets the users permissions, request path and request body,
   * and user id as attributes in the request object.
   */
  public function descifrarRequest($request){
    $permissions = self::descifrarPermisos($request);
    $queryParams = self::descifrarRequestPath($request);
    $decodedBody = self::descifrarCuerpo($request);
    $usuario = usuarios::obtenerUsuarioPorID((int) $request->getAttribute('oauth_user_id'));
    return $request->withAttribute(self::PERMISOS, $permissions)->withAttribute('usuario', $usuario)->withQueryParams($queryParams)->withParsedBody($decodedBody);
  }

}
