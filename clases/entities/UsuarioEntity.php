<?php

class UsuarioEntity extends EntityBase implements DBOCrud{

    var $usuario_id='';
    var $usuario_user='';
    var $usuario_pass='';
    var $usuario_email='';
    var $usuario_nombres='';
    var $usuario_apellidos='';
    var $usuario_fecharegistro='';
    var $usuario_estado='';


    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function storeFormValues(&$options) {
        $pattern = "/[^\.\,\-\_'\"\@\?\!\:\$ a-zA-Z0-9()áéíóúÁÉÍÓÚüÜ]/";
        if (isset($options["usuario_id"])) {
            if ($options["usuario_id"] == "") {
                $options["usuario_id"] = null;
            }
        }
        //Add validation for other fields, specially STRINGS!
        $this->__construct($options);
    }

    public static function getById($usuario_id) {
        try{
            global $pdo;
            $sql = "SELECT * FROM usuario WHERE usuario_id=:usuario_id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new UsuarioEntity($row);
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

            $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM usuario ' . $whereClause . $orderClause .' LIMIT :start, :limit';
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
            $usuarios = array();
            while($row = $stmt->fetch()){
                $usuario = new UsuarioEntity($row);
                $usuarios[] = $usuario;
            }
            return array("usuarios"=>$usuarios, "totalCount"=>$rowTotal["totalCount"]);
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
            $stmt = $pdo->prepare('DELETE FROM usuario WHERE usuario_id=:usuario_id LIMIT 1');
            $stmt->bindParam(':usuario_id', $this->usuario_id, PDO::PARAM_INT);
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
                    'INSERT INTO usuario(
                        usuario_user , 
                        usuario_pass , 
                        usuario_email , 
                        usuario_nombres , 
                        usuario_apellidos , 
                        usuario_fecharegistro , 
                        usuario_estado
                )
                VALUES(
                        :usuario_user , 
                        :usuario_pass , 
                        :usuario_email , 
                        :usuario_nombres , 
                        :usuario_apellidos , 
                        :usuario_fecharegistro , 
                        :usuario_estado
                )'
            );
            $stmt->bindParam(':usuario_user', $this->usuario_user, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_pass', $this->usuario_pass, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $this->usuario_email, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nombres', $this->usuario_nombres, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_apellidos', $this->usuario_apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_fecharegistro', $this->usuario_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_estado', $this->usuario_estado, PDO::PARAM_INT);
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
                    'UPDATE usuario SET
                    usuario_user=:usuario_user,
                    usuario_pass=:usuario_pass,
                    usuario_email=:usuario_email,
                    usuario_nombres=:usuario_nombres,
                    usuario_apellidos=:usuario_apellidos,
                    usuario_fecharegistro=:usuario_fecharegistro,
                    usuario_estado=:usuario_estado
                    WHERE usuario_id=:usuario_id
                    LIMIT 1'
            );
            $stmt->bindParam(':usuario_user', $this->usuario_user, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_pass', $this->usuario_pass, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_email', $this->usuario_email, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_nombres', $this->usuario_nombres, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_apellidos', $this->usuario_apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_fecharegistro', $this->usuario_fecharegistro, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_estado', $this->usuario_estado, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $this->usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            # Affected Rows?
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}