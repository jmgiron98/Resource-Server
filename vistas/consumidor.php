<?php

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Stream;


class consumirResponse {
  /*
   * Invoked function takes in a response object and sends it as a json file.
   */
  public function __invoke(ResponseInterface &$response){
    //definir tipo response
    $response = $response->withHeader('Content-Type', array('application/json; charset=utf8'));

    http_response_code($response->getStatusCode());
    //Status line
    $status_line= 'HTTP/' . $response->getProtocolVersion()
      . ' ' . $response->getStatusCode()
      . ' ' . $response->getReasonPhrase();

    header($status_line);
    //Headers
    $headers = $response->getHeaders();
    foreach($headers as $header=>$value){
      header($header.": ".$value[0]);
    }

    //Body
    $body = json_decode($response->getBody());
    $body = json_encode($body, JSON_PRETTY_PRINT);
    $stream = fopen('php://temp', 'r+');
    $finalBody = new Stream($stream);
    $finalBody->write($body);
    $response->withBody($finalBody);
    echo $response->getBody();
    exit;
  }

}
