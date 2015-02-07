<?php


class Usuario extends UsuarioEntity{
    
    public static function checkLogin($user, $pass){
        try {
            global $pdo;
            $sql = "SELECT * 
                    FROM usuario u                    
                    WHERE usuario_user=:usuario_user and usuario_pass=sha1(:usuario_pass) LIMIT 1";            
            $stmt = $pdo->prepare($sql);
            //$pass = md5($pass);
            $stmt->bindParam(':usuario_user', $user, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            if($row){
                return new Usuario($row);
            }else{
                return NULL;
            }
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
}