<?php
/*
 * File: ScopeRepository.php
 * ----------
 * ScopeRepository implementation for the OAuth 2.0 authentification. 
 * Requires a database that manages permissions. In this case, a database
 * for user types is managed that finds a user by its id and then gets its
 * permissions by its type.
 */

namespace Inmobiges\OAuth2\Repositories;

require_once "controladores/contTiposUsuarios.php";
require "oauth_implement/entities/ScopeEntity.php";

use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Inmobiges\OAuth2\Entities\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use tiposUsuarios;


class ScopeRepository implements ScopeRepositoryInterface
{

  const INITIAL_SCOPE = "initial";// unico scope de todos los login requests
    /**
     * {@inheritdoc}
     */
   public function getScopeEntityByIdentifier($scopeIdentifier)
    {
      $scope = new ScopeEntity($scopeIdentifier);
      return $scope;
    }
    /**
     * {@inheritdoc}
     */
     public function finalizeScopes(
         array $scopes,
         $grantType,
         ClientEntityInterface $clientEntity,
         $userIdentifier = null
     ) {
        $permissions = tiposUsuarios::obtenerPermisosPorUsuario((int)$userIdentifier);
        unset($scopes);
        $scopes= array();
        foreach($permissions as $resource => $permission){
          $scope = new ScopeEntity($resource, $permission);
          $scopes[]=$scope;
        }
        return $scopes;
    }
}
