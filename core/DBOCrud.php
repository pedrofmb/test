<?php


interface DBOCrud{
    
    /**
     * Metodo para realizar la insercion en la BD
     */
    public function insert();        
    
    /**
     * Metodo para realizar la actualizacion en la BD
     */
    public function update();
    
    /**
     * Metodo para realizar la eliminacion en la BD
     */
    public function delete();
    
    /**
     *      
     * @param type $orderOptions Opciones para el orden de los resultados
     * @param type $start Inicio de la consulta (Para paginacion)
     * @param type $limit Limite de consulta (Para paginacion)
     */
    public static function getList($orderOptions=array(), $start = 0, $limit=10);
    
    /**
     * 
     * @param type $whereOptions Opciones para el filtrado de los resultados
     * @param type $orderOptions Opciones para el orden de los resultados
     * @param type $start Inicio de la consulta (Para paginacion)
     * @param type $limit Limite de consulta (Para paginacion)
     */
    public static function getByFields($whereOptions=array(), $orderOptions=array(), $start = 0, $limit=10);
    
    /**
     * 
     * @param type $id Id de la tabla a consultar
     */
    public static function getById($id);
    
    
    
}
?>
