<?php
class Mod_Ticket extends Zend_Db_Table_Abstract {
    
    protected $_name = 'ticket';
    
    /**
     * 
     * @param string $cond
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getAllTickets($cond){
        
        $sql = $this->select()->from( $this->_name)->where($cond);
        
        return $this->fetchAll($sql);
        
    }
}
?>
