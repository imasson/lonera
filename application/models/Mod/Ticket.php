<?php
class Mod_Ticket extends Zend_Db_Table_Abstract {
    
    protected $_name = 'ticket';
    
    /**
     * 
     * @param string $cond
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getAllTickets($cond, $order=""){
        
        $sql = $this->select()->from( $this->_name)->where($cond)->order($order);
        
        return $this->fetchAll($sql);
        
    }
    public function getTktLimit($where,$count,$offset){
        $sql = $this->select()
                    ->from($this->_name)
                    ->where($where)
                    ->limit($count,$offset)
                     
                ;
        return $this->fetchAll($sql);
        
    }
    public function getTktLimitOrder($where,$count,$offset,$order){
        $sql = $this->select()
                    ->from($this->_name)
                    ->where($where)
                    ->limit($count,$offset)
                    ->order($order)
                ;
        return $this->fetchAll($sql);
        
    }
    
    
    public function getCount($where)
    {
        $sql = $this->select()
                    ->from($this->_name,"count(*) AS  totalitems")
                    ->where($where);
        
        $resul = $this->fetchRow($sql);
        
        if ($resul){
            return $resul->totalitems;
        }else return 0;
    }
}
