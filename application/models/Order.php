<?php
class Order extends Zend_Db_Table_Abstract {
    
    protected $_name = 'orders';

    /**
     *
     * @param string $cond
     * @param string $order
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getAllOrders($cond = '', $order = ''){

        $sql = $this->select()->from($this->_name)->where($cond);

        return $this->fetchAll($sql);

    }
    
}
