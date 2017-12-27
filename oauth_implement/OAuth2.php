<?php

require_once 'oauth_implement/repositories/AccessTokenRepository.php';
require_once 'oauth_implement/repositories/ClientRepository.php';
require_once 'oauth_implement/repositories/RefreshTokenRepository.php';
require_once 'oauth_implement/repositories/ScopeRepository.php';
require_once 'oauth_implement/repositories/UserRepository.php';

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepository;
use Inmobiges\OAuth2\Repositories\ClientRepository;
use League\OAuth2\Server\Repositories\RefreshTokenRepository;
use Inmobiges\OAuth2\Repositories\ScopeRepository;
use League\OAuth2\Server\Repositories\UserRepository;

// Init our repositories
$clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
$scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
$accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
$userRepository = new UserRepository(); // instance of UserRepositoryInterface
$refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

// Path to public and private keys
$privateKey = realpath('../private.key');
//$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase
$encryptionKey = 'l3mYBJJbARxMD6nXSu2+zzJst6GcD7krhSopN3RvdQ9s='; // generate using base64_encode(random_bytes(32))

// Setup the authorization server
$server = new \League\OAuth2\Server\AuthorizationServer(
    $clientRepository,
    $accessTokenRepository,
    $scopeRepository,
    $privateKey,
    $encryptionKey
);

$grant = new \League\OAuth2\Server\Grant\PasswordGrant(
     $userRepository,
     $refreshTokenRepository
);

$grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month

// Enable the password grant on the server
$server->enableGrantType(
    $grant,
    new \DateInterval('PT1H') // access tokens will expire after 1 hour
);

$grant = new \League\OAuth2\Server\Grant\RefreshTokenGrant($refreshTokenRepository);
$grant->setRefreshTokenTTL(new \DateInterval('P1M')); // new refresh tokens will expire after 1 month

// Enable the refresh token grant on the server
$server->enableGrantType(
    $grant,
    new \DateInterval('PT1H') // new access tokens will expire after an hour
);
