<?php
/*
 * File: AccessTokenRepository.php
 * ----------
 * AccessTokenRepository implementation for the OAuth 2.0 authentification. 
 * Requires a database that stores refreshTokens and their status.
 */

namespace League\OAuth2\Server\Repositories;

require "oauth_implement/entities/AccessTokenEntity.php";
require_once "controladores/contAccKeys.php";

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntity;
use accessKeys;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
      $userID = (int)$accessTokenEntity->getUserIdentifier();
      $clientID = $accessTokenEntity->getClient()->getIdentifier();
      $expDateTime = $accessTokenEntity->getExpiryDateTime();
      $identifier = $accessTokenEntity->getIdentifier();

      accessKeys::registrar($identifier, $expDateTime, $userID, $clientID);
    }
    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
      accessKeys::revocar($tokenId);
    }
    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
      $tokenStatus = accessKeys::tokenEsActivo($tokenId);
      if($tokenStatus){
        return false; //Token is still active
      } else{
        return true; //Token not active (doesn't exist or was disactivated)
      }
    }
    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);
        return $accessToken;
    }
}
