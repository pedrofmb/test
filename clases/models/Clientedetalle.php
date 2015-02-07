<?php

class Clientedetalle extends ClientedetalleEntity{

	public static function getByCliente($idcliente){
		$wp = array(
			array('field' => 'cliente_id', 'operator'=>'=', 'value'=>$idcliente)
		);
		return self::getByFields($wp);
	}
}