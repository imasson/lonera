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
        if (isset($_GET['client_id']) && $_GET['client_id'] > 0)
            $where .= 'id = ' . $_GET['client_id'];

        /*if (isset($_GET['tktstatus']) && $_GET['tktstatus'] > 0) {
            $where .= ' AND status = ' . $_GET['tktstatus'];
            $nombre = "selected_status_" . $_GET['tktstatus'];
            $this->view->$nombre = 'selected="selected"';
        }

        if (isset($_GET['tktpriority']) && $_GET['tktpriority'] > 0) {
            $where .= ' AND priority = ' . $_GET['tktpriority'];
            $nombre = "selected_priority_" . $_GET['tktpriority'];
            $this->view->$nombre = 'selected="selected"';
        }*/

        $order = new Order();
        $orders = $order->getAllOrders($where, '');
        $this->view->orders = $orders;
    }


    public function newAction()
    {
        $product = new Product();

        $this->view->products = $product->fetchAll();


        //$mod_helptopic = new Mod_HelpTopic();

        //$this->view->helptopics = $mod_helptopic->fetchAll();
    }

    public function saveAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * array(1) { ["tktfile"]=> array(5) { ["name"]=> string(24) "star-trek-2_1-single.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(23) "C:\xampp\tmp\php6DC.tmp" ["error"]=> int(0) ["size"]=> int(47365) } }
         */

        if (isset($_POST['tktTitle']) && !empty($_POST['tktTitle'])) {


            // ["tktTitle"]=> string(15) "asdasdasdasdasd" ["tktDescription"]=> string(0) "" ["tktHelpTopic"]=> string(1) "0" ["tktPriority"]=> string(1) "0" 
            $mod_ticket = new Mod_Ticket();

            $tkt = $mod_ticket->createRow();

            $tkt->title = $_POST['tktTitle'];
            $tkt->description = $_POST['tktDescription'];
            $tkt->id_helptopic = $_POST['tktHelpTopic'];
            //$tkt->status        = $_POST['']; DEFAULT 1
            $tkt->created_date = date("Y-m-d h:i:s");
            $tkt->updated_date = date("Y-m-d h:i:s");
            $tkt->created_user = Tkt_User::getUser();

            if ($_POST['tktPriority'] > 0)
                $tkt->priority = $_POST['tktPriority'];

            $id_ticket = $tkt->save();


            //TRATAR ERROR DE ARCHIVO

            if (isset($_FILES['tktfile1']) && $_FILES['tktfile1']['error'] == 0 && $_FILES['tktfile1']['size'] > 0) {
                $config = Common_Config::getInstance();
                Common_Log::getInstance()->log(print_r($_FILES, true), zend_log::CRIT);
                $tmp_filename = $_FILES['tktfile1']['tmp_name'];

                $destination = $config->tkttool->upload->dir;

                $filename = $id_ticket . $_FILES['tktfile1']['name'];
                //$filesystem_name = sha1($filename);

                if (move_uploaded_file($tmp_filename, $destination . $filename)) {
                    echo "SUBIOO";
                    $tkt->attached = $filename;
                    $id_ticket = $tkt->save();
                } else {
                    echo "NOOO";
                }
            }


            $_confini = Common_Config::getInstance();


            $this->redirect("/order/list");
        } else {
            $this->redirect("/order/new");
        }
    }

}