<?php

class Categoria extends CategoriaEntity{

	public static function listar(){
		$wp = array();
		$op = array(
			array("field"=>"categoria_orden", "order"=>"ASC")
		);
		$start = 0;
		$limit = 50;
		return self::getByFields($wp, $op, $start, $limit);
	}
}

?>