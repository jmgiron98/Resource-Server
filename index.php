<?php
/*
 * File: index.php
 * ----------
 * Reads in a request, calls on the authorization middleware and sends the request
 * to the resource router. The request is expected to be formatted as a json file.
 */
require_once 'utilidades/required_files.php';

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Stream;

$request = ServerRequest::fromGlobals();

$response = new Response();

$router = new Router();

//Iniciar consumidor de respuesta
//Initialize response consumer
$consumir = new consumirResponse();

// Manejo de excepciones
// Exception Handler
set_exception_handler(function($exception) use ($response, $consumir){
  if($exception->getCode()) {
		$response = $response->withStatus($exception->getCode());
	} else {
		$response = $response->withStatus(500);
	}
  $cuerpo =array("Error" => $exception->getMessage());
  $string = json_encode($cuerpo);
  $response->getBody()->write($string);
  $consumir($response);
}
);

//Extraer segmento de la url
//Extract URL path
if(isset($_GET['PATH_INFO']))
  $peticion = explode('/', $_GET['PATH_INFO']);
else
  throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("No se reconoce la peticion"));


//Obtener recurso
//Get requested resource
$recurso = array_shift($peticion);

//caso exclusivo request de access_token
//case for new access token
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post' && $recurso == "access_token"){
  $response = access_token::post($server, $request, $response);
  $consumir($response);
}

// performs authentification and then passes on to router
$return = $middleware($request, $response, $router);

//returns the response
$consumir($return);
