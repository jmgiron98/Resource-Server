<?php
/*
 * File: ClientRepository.php
 * ----------
 * ClientRepository implementation for the OAuth 2.0 authentification. 
 */

namespace Inmobiges\OAuth2\Repositories;

require "oauth_implement/entities/ClientEntity.php";

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Inmobiges\OAuth2\Entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        $clients = [
            'sample_name' => [
                'secret'          => password_hash('sample_client_password', PASSWORD_BCRYPT),
                'name'            => 'sample_client_name',
                'redirect_uri'    => 'redirect_uri',
                'is_confidential' => true,
            ],
        ];
        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            return;
        }
        if (
            $mustValidateSecret === true
            && $clients[$clientIdentifier]['is_confidential'] === true
            && password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        ) {
            return;
        }

        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clients[$clientIdentifier]['name']);
        $client->setRedirectUri($clients[$clientIdentifier]['redirect_uri']);
        return $client;
    }
}
