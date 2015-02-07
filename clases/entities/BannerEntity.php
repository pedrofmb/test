<?php

class BannerEntity extends EntityBase implements DBOCrud{

    var $banner_id='';
    var $banner_estado='';
    var $banner_fecharegistro='';
    var $imagen_id='';
    var $bannertipoimagen_id='';
    var $banner_titulo='';
    var $banner_descripcion='';


    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function storeFormValues(&$options) {
        $pattern = "/[^\.\,\-\_'\"\@\?\!\:\$ a-zA-Z0-9()áéíóúÁÉÍÓÚüÜ]/";
        if (isset($options["banner_id"])) {
            if ($options["banner_id"] == "") {
                $options["banner_id"] = null;
            }
        }
        //Add validation for other fields, specially STRINGS!
        $this->__construct($options);
    }

    public static function getById($banner_id) {
        try{
            global $pdo;
            $sql = "SELECT * FROM banner WHERE banner_id=:banner_id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':banner_id', $banner_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new BannerEntity($row);
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

            $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM banner ' . $whereClause . $orderClause .' LIMIT :start, :limit';
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
            $banneres = array();
            while($row = $stmt->fetch()){
                $banner = new BannerEntity($row);
                $banneres[] = $banner;
            }
            return array("banneres"=>$banneres, "totalCount"=>$rowTotal["totalCount"]);
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
            $stmt = $pdo->prepare('DELETE FROM banner WHERE banner_id=:banner_id LIMIT 1');
            $stmt->bindParam(':banner_id', $this->banner_id, PDO::PARAM_INT);
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
                    'INSERT INTO banner(
                        banner_estado , 
                        banner_fecharegistro , 
                        imagen_id , 
                        bannertipoimagen_id , 
                        banner_titulo , 
                        banner_descripcion
                )
                VALUES(
                        :banner_estado , 
                        :banner_fecharegistro , 
                        :imagen_id , 
                        :bannertipoimagen_id , 
                        :banner_titulo , 
                        :banner_descripcion
                )'
            );
            $stmt->bindParam(':banner_estado', $this->banner_estado, PDO::PARAM_STR);
            $stmt->bindParam(':banner_fecharegistro', $this->banner_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':imagen_id', $this->imagen_id, PDO::PARAM_INT);
            $stmt->bindParam(':bannertipoimagen_id', $this->bannertipoimagen_id, PDO::PARAM_INT);
            $stmt->bindParam(':banner_titulo', $this->banner_titulo, PDO::PARAM_STR);
            $stmt->bindParam(':banner_descripcion', $this->banner_descripcion, PDO::PARAM_STR);
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
                    'UPDATE banner SET
                    banner_estado=:banner_estado,
                    banner_fecharegistro=:banner_fecharegistro,
                    imagen_id=:imagen_id,
                    bannertipoimagen_id=:bannertipoimagen_id,
                    banner_titulo=:banner_titulo,
                    banner_descripcion=:banner_descripcion
                    WHERE banner_id=:banner_id
                    LIMIT 1'
            );
            $stmt->bindParam(':banner_estado', $this->banner_estado, PDO::PARAM_STR);
            $stmt->bindParam(':banner_fecharegistro', $this->banner_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':imagen_id', $this->imagen_id, PDO::PARAM_INT);
            $stmt->bindParam(':bannertipoimagen_id', $this->bannertipoimagen_id, PDO::PARAM_INT);
            $stmt->bindParam(':banner_titulo', $this->banner_titulo, PDO::PARAM_STR);
            $stmt->bindParam(':banner_descripcion', $this->banner_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':banner_id', $this->banner_id, PDO::PARAM_INT);
            $stmt->execute();
            # Affected Rows?
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}

?>