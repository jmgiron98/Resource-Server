<?php

class ExcepcionApi extends Exception
{
	public function __construct($codigo=500, $mensaje="Error Desconocido")
	{
		$this->message = $mensaje;
		$this->code = $codigo;
	}
}
