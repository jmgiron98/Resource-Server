<?php
/*
 * File: RefreshTokenRepository.php
 * ----------
 * RefreshTokenRepository implementation for the OAuth 2.0 authentification. 
 * Requires a database that stores refreshTokens and their status.
 */
namespace League\OAuth2\Server\Repositories;

require "oauth_implement/entities/RefreshTokenEntity.php";
require_once "controladores/contRefKeys.php";

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntity;
use refreshKeys;


class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntityInterface)
    {
      $accTokenIdentifier = $refreshTokenEntityInterface->getAccessToken()->getIdentifier();
      $expDateTime = $refreshTokenEntityInterface->getExpiryDateTime();
      $identifier = $refreshTokenEntityInterface->getIdentifier();

      refreshKeys::registrar($identifier, $expDateTime, $accTokenIdentifier);
    }
    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
      refreshKeys::revocar($tokenId);
    }
    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
      $tokenStatus = refreshKeys::tokenEsActivo($tokenId);
      if($tokenStatus){
        return false; //Token is still active
      } else{
        return true; //Token not active (doesn't exist or was disactivated)
      }
    }
    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
      return new RefreshTokenEntity();
    }
}
