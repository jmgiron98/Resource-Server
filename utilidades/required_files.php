<?php

//Controladores de recursos publicos
require_once 'controladores/contInterface.php';
require_once 'controladores/contUsuarios.php';
require_once 'controladores/contTiposUsuarios.php';


require_once 'modelos/abstractModelo.php';


require_once 'vistas/VistaJson.php';
require_once 'utilidades/ExcepcionApi.php';
require_once 'datos/recursos_existentes.php';
require_once 'vistas/consumidor.php';
require_once 'router.php';

require_once __DIR__ . '/../vendor/autoload.php';

// Setup de OAuth 2.0
require_once 'oauth_implement/OAuth2.php';
require_once 'oauth_implement/access_token.php';
require_once 'oauth_implement/oauth2_middleware.php';
