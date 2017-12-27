<?php

namespace Inmobiges\OAuth2\Entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity implements ScopeEntityInterface
{
  use EntityTrait;

  private $permissionLevel;

  public function __construct($identifier, $permissionLevel=0){
    $this->identifier = $identifier."/".$permissionLevel;
    $this->permissionLevel = $permissionLevel;
  }

  /*
   * Get the scope's permission level
   *
   * Premission levels:
   * 0. No permission
   * 1. Read
   * 2. Create
   * 3. Update
   * 4. Delete
   * Permissions are set in increasing level, so a user with permission 3 can also
   * create and delete.
   */
  public function getPermissionLevel(){
    return $this->permissionLevel;
  }

  public function setPermissionLevel($permissionLevel){
    $this->permissionLevel = $permissionLevel;
  }

  public function jsonSerialize()
  {
    return $this->getIdentifier();
  }

}
