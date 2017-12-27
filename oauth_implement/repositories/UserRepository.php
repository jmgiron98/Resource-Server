<?php
/*
 * File: UserRepository.php
 * ----------
 * UserRepository implementation for the OAuth 2.0 authentification. 
 * Requires a database that stores Users and can verify a user is valid.
 */

namespace League\OAuth2\Server\Repositories;

require "oauth_implement/entities/UserEntity.php";
require_once "controladores/contUsuarios.php";

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\UserEntity;
use usuarios;


class UserRepository implements UserRepositoryInterface
{
	/**
     * Get a user entity.
     *
     * @param string                $username     //login hecho con email, no username
     * @param string                $password
     * @param string                $grantType    The grant type used //don't check grant type
     * @param ClientEntityInterface $clientEntity //don't check client entity
     *
     * @return UserEntityInterface
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ){
    	$id = usuarios::loguear($username, $password); //login username, returns user id
    	if($id != null){
     		$userEntity = new UserEntity($id);
     		return $userEntity;
    	} else{
    		throw new Exception(utf8_encode("Correo o contraseña inválidos"), 400);
    	}

    }
}
