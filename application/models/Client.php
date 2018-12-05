<?php
class Client extends Zend_Db_Table_Abstract {
    
    protected $_name = 'clients';

    /**
     *
     * @param string $cond
     * @param string $order
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getAllClients($cond = '', $order = ''){

        $sql = $this->select()->from($this->_name)->where($cond);

        return $this->fetchAll($sql);

    }
    
}
