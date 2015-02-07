<?php

class SubcategoriaEntity extends EntityBase implements DBOCrud{

    var $subcategoria_id='';
    var $subcategoria_nombre='';
    var $categoria_id='';
    var $subcategoria_ruta='';
    var $subcategoria_padre='';


    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function storeFormValues(&$options) {
        $pattern = "/[^\.\,\-\_'\"\@\?\!\:\$ a-zA-Z0-9()áéíóúÁÉÍÓÚüÜ]/";
        if (isset($options["subcategoria_id"])) {
            if ($options["subcategoria_id"] == "") {
                $options["subcategoria_id"] = null;
            }
        }
        //Add validation for other fields, specially STRINGS!
        $this->__construct($options);
    }

    public static function getById($subcategoria_id) {
        try{
            global $pdo;
            $sql = "SELECT * FROM subcategoria WHERE subcategoria_id=:subcategoria_id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':subcategoria_id', $subcategoria_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new SubcategoriaEntity($row);
            }else{
                return false;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public static function getByFields($whereParams = array(), $orderParams = array(), $start = 0, $limit = LIMIT_RESULT) {
        try {
            global $pdo;

            $orderClause = '';
            if(count($orderParams)>0){
                $arrOrderParams = array();
                foreach ($orderParams as $op){
                    $arrOrderParams[] = sprintf("%s %s", $op["field"], $op["order"]);
                }
                $orderClause = ' ORDER by '. join(', ', $arrOrderParams);
            }

            $whereClause = '';
            if(count($whereParams)>0){
                $cadWhere = '';
                $i=1;
                foreach($whereParams as $wp){
                    $cadWhere .= sprintf("%s %s :%s", $wp["field"], $wp["operator"], preg_replace('/[^a-zA-Z0-9]+/', '_', $wp["field"]));
                    if($i<(count($whereParams))){
                        $cadWhere .= ' ' . $wp["conjunction"] . ' ';
                    }
                    $i++;
                }
                $whereClause = ' WHERE ' . $cadWhere;
            }

            $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM subcategoria ' . $whereClause . $orderClause .' LIMIT :start, :limit';
            $stmt = $pdo->prepare($query);
            if(count($whereParams)>0){
                foreach($whereParams as $wp){
                    if($wp["operator"] == "="){
                        $stmt->bindParam(':' . $wp["field"], $wp["value"]);
                    }else{
                        $wc_value = '%'.$wp["value"].'%';           //wildcards value
                        $stmt->bindParam(':' . $wp["field"], $wc_value);
                    }
                    //$stmt->bindParam(':'.preg_replace('/[^a-zA-Z0-9]+/', '_', $wp["field"]), $wp["value"]);
                }
            }
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $pdo->query("SELECT FOUND_ROWS() AS totalCount");
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $rowTotal = $result->fetch();
            $subcategorias = array();
            while($row = $stmt->fetch()){
                $subcategoria = new SubcategoriaEntity($row);
                $subcategorias[] = $subcategoria;
            }
            return array("subcategorias"=>$subcategorias, "totalCount"=>$rowTotal["totalCount"]);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public static function getList($orderParams = array(), $start = 0, $limit = LIMIT_RESULT) {
        return self::getByFields(array(), $orderParams, $start, $limit);
    }

    public function delete() {
        try {
            global $pdo;
            $stmt = $pdo->prepare('DELETE FROM subcategoria WHERE subcategoria_id=:subcategoria_id LIMIT 1');
            $stmt->bindParam(':subcategoria_id', $this->subcategoria_id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() === 1){
                return true;
            }else{
                return false;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function insert() {
        try {
            global $pdo;
            $stmt = $pdo->prepare(
                    'INSERT INTO subcategoria(
                        subcategoria_nombre , 
                        categoria_id , 
                        subcategoria_ruta , 
                        subcategoria_padre
                )
                VALUES(
                        :subcategoria_nombre , 
                        :categoria_id , 
                        :subcategoria_ruta , 
                        :subcategoria_padre
                )'
            );
            $stmt->bindParam(':subcategoria_nombre', $this->subcategoria_nombre, PDO::PARAM_STR);
            $stmt->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT);
            $stmt->bindParam(':subcategoria_ruta', $this->subcategoria_ruta, PDO::PARAM_STR);
            $stmt->bindParam(':subcategoria_padre', $this->subcategoria_padre, PDO::PARAM_INT);
            $stmt->execute();

            # Affected Rows?
            if($stmt->rowCount() === 1){
                return $pdo->lastInsertId();
            }else{
                return false;
            }            
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage() . '\n '. $e->getTraceAsString();
        }
    }

    public function update() {
        try {
            global $pdo;
            $stmt = $pdo->prepare(
                    'UPDATE subcategoria SET
                    subcategoria_nombre=:subcategoria_nombre,
                    categoria_id=:categoria_id,
                    subcategoria_ruta=:subcategoria_ruta,
                    subcategoria_padre=:subcategoria_padre
                    WHERE subcategoria_id=:subcategoria_id
                    LIMIT 1'
            );
            $stmt->bindParam(':subcategoria_nombre', $this->subcategoria_nombre, PDO::PARAM_STR);
            $stmt->bindParam(':categoria_id', $this->categoria_id, PDO::PARAM_INT);
            $stmt->bindParam(':subcategoria_ruta', $this->subcategoria_ruta, PDO::PARAM_STR);
            $stmt->bindParam(':subcategoria_padre', $this->subcategoria_padre, PDO::PARAM_INT);
            $stmt->bindParam(':subcategoria_id', $this->subcategoria_id, PDO::PARAM_INT);
            $stmt->execute();
            # Affected Rows?
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}