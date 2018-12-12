<?php
class Product extends Zend_Db_Table_Abstract {
    
    protected $_name = 'order_type';

    /**
     *
     * @param string $cond
     * @param string $order
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los productos
     */
    public function getAllProducts($cond = '', $order = ''){

        $sql = $this->select()->from($this->_name)->where($cond);

        return $this->fetchAll($sql);

    }
    
}
