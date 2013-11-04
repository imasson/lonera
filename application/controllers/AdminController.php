<?php
class AdminController extends Zend_Controller_Action {

    public function init() {
        
        if (!Tkt_User::isvalid() || !Tkt_User::issupport() ) {
            $this->redirect("/");
        }
        
        $this->_helper->layout->setLayout('adminlayout');
        
        
    }

    public function indexAction() {
        $this->view->selected_status_1 = "";
        $this->view->selected_status_2 = "";
        $this->view->selected_status_3 = "";
        $this->view->selected_status_4 = "";
        $this->view->selected_status_5 = "";
        
        $this->selected_priority_1 = $this->selected_priority_2 = $this->selected_priority_3 = $this->selected_priority_4 = "";
        
        $where = "true ";
        
         if( isset( $_GET['tktid'] ) && $_GET['tktid'] > 0 )
            $where .= ' AND id = '.$_GET['tktid'];
        
        if( isset( $_GET['tktstatus'] ) && $_GET['tktstatus'] > 0 ){
            $where .= ' AND status = '.$_GET['tktstatus'];
            $nombre = "selected_status_".$_GET['tktstatus'];
            $this->view->$nombre = 'selected="selected"';
        }
        
        if( isset( $_GET['tktpriority'] ) && $_GET['tktpriority'] > 0 ){
            $where .= ' AND priority = '.$_GET['tktpriority'];
            $nombre = "selected_priority_".$_GET['tktpriority'];
            $this->view->$nombre = 'selected="selected"';
        }
        
        $mod_tickets = new Mod_Ticket();
        $tickets = $mod_tickets->fetchAll($where);
        
        $this->view->tickets = $tickets;
    }
    
    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        Tkt_User::unsetUser();

        $this->redirect("/");
    }
}
?>
