<?php
/**
 * Clase que envuelve una instancia de la clase PDO
 * para el manejo de la base de modelos
 */

/* File:conexionBD.php
 * ---------
 * Wrapper for an instance of the PDO class for the management of the database
 */

require_once 'login_mysql.php';


class ConexionBD
{
	/**
	 * Unica instancia de la clase
	 */
	// sole class instance
	private static $db = null;

	/**
	 * Instancia de PDO
	 */
	// PDO instance
	private static $pdo;

	final private function __construct()
	{
		try{
			// Crear nueva conexion PDO
			self::obtenerBD();
		} catch(PDOException $e) {
			//Manejo de excepciones
		}
	}

	/**
	 * Retorna en la unica instancia de la clase
	 * @return ConexionBD|null
	 */
	/*
	 * Function: obtenerInstancia
	 * ------------
	 * Obtain class instance
	 */
	public static function obtenerInstancia()
	{
		if (self::$db === null){
				self::$db = new self();
		}
		return self::$db;
	}

	/**
	 * Crear una nueva conexion PDO basada en las constantes de conexion
	 * @return PDO Objeto PDO
	 */
	/*
	 * Function: obtenerBD
	 * ----------
	 * Established a new PDO conexion based on the conection constants defined in login_mysql.php.
	 */
	public function obtenerBD()
	{
		if (self::$pdo == null){
				self::$pdo = new PDO(
				'mysql:dbname=' .BASE_DE_DATOS .
				';host=' . NOMBRE_HOST .";",
				USUARIO,
				CONTRASENA,
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
			);

			// Habilitar excepciones
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$pdo;
	}


	/**
	 * Evita la clonacion del objeto
	 */
	//Prevents object cloning
	final protected function __clone()
	{
	}

	function _destructor()
	{
		self::$pdo = null;
	}
}
