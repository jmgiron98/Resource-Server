<?php
/*
 * File: recursos_existentes.php
 * ---------
 * Contains all existing resources that can be accessed through a request.
 * For authentification purposes, at least the user and usertype resources must
 * exist.
 */

class recursos
{
  public static $recursos_existentes = array(
   'usuarios', //users
   'tiposUsuarios' //user types
  );

}
