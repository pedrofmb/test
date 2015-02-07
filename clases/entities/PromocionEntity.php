<?php

class PromocionEntity extends EntityBase implements DBOCrud{

    var $promocion_id='';
    var $promocion_titulo='';
    var $promocion_descripcion='';
    var $promocion_fecharegistro='';
    var $promocion_vigente='';
    var $imagen_id='';


    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function storeFormValues(&$options) {
        $pattern = "/[^\.\,\-\_'\"\@\?\!\:\$ a-zA-Z0-9()áéíóúÁÉÍÓÚüÜ]/";
        if (isset($options["promocion_id"])) {
            if ($options["promocion_id"] == "") {
                $options["promocion_id"] = null;
            }
        }
        //Add validation for other fields, specially STRINGS!
        $this->__construct($options);
    }

    public static function getById($promocion_id) {
        try{
            global $pdo;
            $sql = "SELECT * FROM promocion WHERE promocion_id=:promocion_id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':promocion_id', $promocion_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new PromocionEntity($row);
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

            $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM promocion ' . $whereClause . $orderClause .' LIMIT :start, :limit';
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
            $promociones = array();
            while($row = $stmt->fetch()){
                $promocion = new PromocionEntity($row);
                $promociones[] = $promocion;
            }
            return array("promociones"=>$promociones, "totalCount"=>$rowTotal["totalCount"]);
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
            $stmt = $pdo->prepare('DELETE FROM promocion WHERE promocion_id=:promocion_id LIMIT 1');
            $stmt->bindParam(':promocion_id', $this->promocion_id, PDO::PARAM_INT);
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
                    'INSERT INTO promocion(
                        promocion_titulo , 
                        promocion_descripcion , 
                        promocion_fecharegistro , 
                        promocion_vigente , 
                        imagen_id
                )
                VALUES(
                        :promocion_titulo , 
                        :promocion_descripcion , 
                        :promocion_fecharegistro , 
                        :promocion_vigente , 
                        :imagen_id
                )'
            );
            $stmt->bindParam(':promocion_titulo', $this->promocion_titulo, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_descripcion', $this->promocion_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_fecharegistro', $this->promocion_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_vigente', $this->promocion_vigente, PDO::PARAM_INT);
            $stmt->bindParam(':imagen_id', $this->imagen_id, PDO::PARAM_INT);
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
                    'UPDATE promocion SET
                    promocion_titulo=:promocion_titulo,
                    promocion_descripcion=:promocion_descripcion,
                    promocion_fecharegistro=:promocion_fecharegistro,
                    promocion_vigente=:promocion_vigente,
                    imagen_id=:imagen_id
                    WHERE promocion_id=:promocion_id
                    LIMIT 1'
            );
            $stmt->bindParam(':promocion_titulo', $this->promocion_titulo, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_descripcion', $this->promocion_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_fecharegistro', $this->promocion_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':promocion_vigente', $this->promocion_vigente, PDO::PARAM_INT);
            $stmt->bindParam(':imagen_id', $this->imagen_id, PDO::PARAM_INT);
            $stmt->bindParam(':promocion_id', $this->promocion_id, PDO::PARAM_INT);
            $stmt->execute();
            # Affected Rows?
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}

