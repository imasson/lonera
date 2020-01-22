<?php

class OrderController extends Zend_Controller_Action
{

    public function init()
    {
        if (!Tkt_User::isvalid()) {
            $this->redirect("/");
        }

        if (Tkt_User::issupport()) {
            $this->redirect("/admin");
        }
        $this->view->user = Tkt_User::getUser();
    }

    public function indexAction()
    {
        $this->view->body_class = "";

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $this->redirect("/public/list");
    }

    public function listAction()
    {

        $this->view->selected_status_1 = "";
        $this->view->selected_status_2 = "";
        $this->view->selected_status_3 = "";
        $this->view->selected_status_4 = "";
        $this->view->selected_status_5 = "";

        $this->selected_priority_1 = $this->selected_priority_2 = $this->selected_priority_3 = $this->selected_priority_4 = "";

        //$where = "created_user='" . Tkt_User::getUser() . "'";
        $where = 'id > 0 ';
        if (isset($_GET['client_id']) && $_GET['client_id'] > 0){
            $where .= ' and client_id = ' . $_GET['client_id'];

        }
        if (isset($_GET['type_id']) && $_GET['type_id'] > 0){
            $where .= ' and type_id = ' . $_GET['type_id'];
        }

        if (isset($_GET['payment_method']) && $_GET['payment_method']){
            $where .= ' and payment_method = "' . $_GET['payment_method'] . '"';
            $this->view->{$_GET['payment_method']} = 'selected="selected"';
        }

        if (isset($_GET['order_start']) && $_GET['order_start'] && isset($_GET['order_end']) && $_GET['order_end'] ) {
            $date = strtotime($_GET['order_start']);
            $start = new Zend_Db_Expr("FROM_UNIXTIME({$date})");
            $date = strtotime($_GET['order_end']);
            $end = new Zend_Db_Expr("FROM_UNIXTIME({$date})");

            $where .= ' AND created_at BETWEEN '.$start.' AND '.$end.'';

        }

        if (isset($_GET['due_date']) && $_GET['due_date']) {
            $date = strtotime($_GET['due_date']);
            $start = new Zend_Db_Expr("FROM_UNIXTIME({$date})");

            $where .= ' AND check_date < '. $start .'';
        }

        $order = new Order();
        $orders = $order->getAllOrders($where, '');
        $this->view->orders = $orders;
    }


    public function newAction()
    {
        $product = new Product();
        $client = new Client();

        $this->view->products = $product->fetchAll();
        $this->view->clients = $client->fetchAll();


        //$mod_helptopic = new Mod_HelpTopic();

        //$this->view->helptopics = $mod_helptopic->fetchAll();
    }

    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * array(1) { ["tktfile"]=> array(5) { ["name"]=> string(24) "star-trek-2_1-single.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(23) "C:\xampp\tmp\php6DC.tmp" ["error"]=> int(0) ["size"]=> int(47365) } }
         */

        //if (isset($_POST['name']) && !empty($_POST['name'])) {


        // ["tktTitle"]=> string(15) "asdasdasdasdasd" ["tktDescription"]=> string(0) "" ["tktHelpTopic"]=> string(1) "0" ["tktPriority"]=> string(1) "0"
        $order = new Order();

        $order = $order->createRow();

        $order->client_id = $_POST['client'];
        $order->total = $_POST['total'];
        $order->paid_total = $_POST['paid_total'];
        $order->payment_method = $_POST['payment_method'];
        if (isset($_POST['check_date'])) {
            $date = strtotime($_POST['check_date']);
            $order->check_date = new Zend_Db_Expr("FROM_UNIXTIME({$date})");
            //var_dump($order->check_date);die;
        }

        $order->type_id = $_POST['product'];
        $order->meters = $_POST['meters'];
        $order->description = $_POST['description'];
        $order->created_at = new Zend_Db_Expr('NOW()');
        //var_dump($order->created_at);die;
        $id = $order->save();


        $this->redirect("/order/list");
    }

    public function saveAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * array(1) { ["tktfile"]=> array(5) { ["name"]=> string(24) "star-trek-2_1-single.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(23) "C:\xampp\tmp\php6DC.tmp" ["error"]=> int(0) ["size"]=> int(47365) } }
         */

        //if (isset($_POST['name']) && !empty($_POST['name'])) {


        // ["tktTitle"]=> string(15) "asdasdasdasdasd" ["tktDescription"]=> string(0) "" ["tktHelpTopic"]=> string(1) "0" ["tktPriority"]=> string(1) "0"
        $order = new Order();

        $order = $order->createRow();

        $order->client_id = $_POST['client'];
        $order->total = $_POST['total'];
        $order->paid_total = $_POST['paid_total'];
        $order->payment_method = $_POST['payment_method'];
        $order->check_date = $_POST['check_date'];
        $order->type_id = $_POST['product'];
        $order->meters = $_POST['meters'];
        $order->description = $_POST['description'];
        $order->created_at = date("d-m-Y h:i:s");

        $id = $order->save();


        $this->redirect("/product/list");
        //} else {
        //    $this->redirect("/product/new");
        //}
    }

}