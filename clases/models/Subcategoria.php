<?php

class Subcategoria extends SubcategoriaEntity {

    public static function getSubCategoriaPadre($idcategoria) {
        $wp = array(
            array('field' => 'categoria_id', 'operator' => '=', 'value' => $idcategoria, 'conjunction' => 'and'),
            array('field' => 'subcategoria_padre', 'operator' => '=', 'value' => 0)
        );
        $op = array(
            array('field' => 'subcategoria_nombre', 'order'=>'ASC')
        );
        $subcategorias = self::getByFields($wp, $op);

        foreach ($subcategorias["subcategorias"] as $sc) {
            $sc->tiene_hijos = self::tieneSubcatgoriasHijas($sc->subcategoria_id);
        }
        
        return $subcategorias;
    }

    public static function getSubCategoriaHijas($idcategoria, $idsubcategoria) {
        $wp = array(
            array('field' => 'categoria_id', 'operator' => '=', 'value' => $idcategoria, 'conjunction' => 'and'),
            array('field' => 'subcategoria_padre', 'operator' => '=', 'value' => $idsubcategoria)
        );
        $op = array(
            array('field' => 'subcategoria_nombre', 'order'=>'ASC')
        );
        return self::getByFields($wp, $op);
    }

    public static function tieneSubcatgoriasHijas($idsubcategoria) {
        try {
            global $pdo;
            $sql = "SELECT count(subcategoria_id) as cuenta FROM subcategoria WHERE subcategoria_padre=:subcategoria_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':subcategoria_id', $idsubcategoria, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row["cuenta"]*1>0;            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
