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

    /**
     *
     * @param string $cond
     * @param string $order
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getAllOrdersByProd($cond = '', $order = ''){

        $sql = "SELECT type_id, COUNT(*) c, SUM(meters) mts FROM orders GROUP BY type_id HAVING c > 0 order by c desc limit 5";

        return $this->getAdapter()->query($sql)->fetchAll();

    }

    /**
     *
     * @param string $cond
     * @param string $order
     * @return Zend_Db_Table_Rowset_Abstract retorna todos los tickets
     */
    public function getTotals($cond = '', $order = ''){

        $sql = "SELECT SUM(total) as total, SUM(paid_total) as paid_total FROM orders where created_at between '2008-07-04' and '2012-05-11'";

        return $this->getAdapter()->query($sql)->fetchAll();

    }


}
