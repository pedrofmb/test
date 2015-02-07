<?php

class ContactoEntity extends EntityBase implements DBOCrud{

    var $contacto_id='';
    var $contacto_email='';
    var $contacto_nombres='';
    var $contacto_asunto='';
    var $contacto_mensaje='';
    var $contacto_fecharegistro='';
    var $contacto_ip='';


    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function storeFormValues(&$options) {
        $pattern = "/[^\.\,\-\_'\"\@\?\!\:\$ a-zA-Z0-9()áéíóúÁÉÍÓÚüÜ]/";
        if (isset($options["contacto_id"])) {
            if ($options["contacto_id"] == "") {
                $options["contacto_id"] = null;
            }
        }
        //Add validation for other fields, specially STRINGS!
        $this->__construct($options);
    }

    public static function getById($contacto_id) {
        try{
            global $pdo;
            $sql = "SELECT * FROM contacto WHERE contacto_id=:contacto_id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':contacto_id', $contacto_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new ContactoEntity($row);
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

            $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM contacto ' . $whereClause . $orderClause .' LIMIT :start, :limit';
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
            $contactos = array();
            while($row = $stmt->fetch()){
                $contacto = new ContactoEntity($row);
                $contactos[] = $contacto;
            }
            return array("contactos"=>$contactos, "totalCount"=>$rowTotal["totalCount"]);
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
            $stmt = $pdo->prepare('DELETE FROM contacto WHERE contacto_id=:contacto_id LIMIT 1');
            $stmt->bindParam(':contacto_id', $this->contacto_id, PDO::PARAM_INT);
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
                    'INSERT INTO contacto(
                        contacto_email , 
                        contacto_nombres , 
                        contacto_asunto , 
                        contacto_mensaje , 
                        contacto_fecharegistro , 
                        contacto_ip
                )
                VALUES(
                        :contacto_email , 
                        :contacto_nombres , 
                        :contacto_asunto , 
                        :contacto_mensaje , 
                        :contacto_fecharegistro , 
                        :contacto_ip
                )'
            );
            $stmt->bindParam(':contacto_email', $this->contacto_email, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_nombres', $this->contacto_nombres, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_asunto', $this->contacto_asunto, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_mensaje', $this->contacto_mensaje, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_fecharegistro', $this->contacto_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_ip', $this->contacto_ip, PDO::PARAM_STR);
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
                    'UPDATE contacto SET
                    contacto_email=:contacto_email,
                    contacto_nombres=:contacto_nombres,
                    contacto_asunto=:contacto_asunto,
                    contacto_mensaje=:contacto_mensaje,
                    contacto_fecharegistro=:contacto_fecharegistro,
                    contacto_ip=:contacto_ip
                    WHERE contacto_id=:contacto_id
                    LIMIT 1'
            );
            $stmt->bindParam(':contacto_email', $this->contacto_email, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_nombres', $this->contacto_nombres, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_asunto', $this->contacto_asunto, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_mensaje', $this->contacto_mensaje, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_fecharegistro', $this->contacto_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_ip', $this->contacto_ip, PDO::PARAM_STR);
            $stmt->bindParam(':contacto_id', $this->contacto_id, PDO::PARAM_INT);
            $stmt->execute();
            # Affected Rows?
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}

?>