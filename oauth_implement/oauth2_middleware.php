<?php
require_once 'oauth_implement/repositories/AccessTokenRepository.php';

use League\OAuth2\Server\Repositories\AccessTokenRepository;

// Init our repositories
$accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface

// Path to authorization server's public key
$publicKeyPath = realpath('path_to_public_key');

// Setup the authorization server
$resourceServer = new \League\OAuth2\Server\ResourceServer(
    $accessTokenRepository,
    $publicKeyPath
);

//incializar OAuth 2.0 Middleware
$middleware = new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($resourceServer);
