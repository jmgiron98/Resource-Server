<?php

// PSR-7 Request and Response
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\StreamWrapper;
use GuzzleHttp\Psr7\Stream;

class access_token{
  public static function post($server, $request, $response)
  {
    try {

      // Try to respond to the access token request
      $response = $server->respondToAccessTokenRequest($request, $response);
      return $response;
    } catch (OAuthServerException $exception) {
            // All instances of OAuthServerException can be converted to a PSR-7 response
            return $exception->generateHttpResponse($response);
    }catch (\Exception $exception) {
        // Unknown exception
        //throw new ExcepcionApi($exception->getCode(), $exception->getMessage());
        $body = $response->getBody();
        $body->write($exception->getMessage());
        return $response->withStatus(500)->withBody($body);
    }
  }
}
