<?php

class AdminController extends Zend_Controller_Action {
protected $_uri = '/';

    public function init() {

        $action = $this->getParam("action");

        if ($action != "login") {

            if (!Tkt_User::isvalid()) {
                $this->redirect("/admin/login");
            } elseif (!Tkt_User::issupport()) {
                $this->redirect("/");
            }

            
            $this->view->user=Tkt_User::getUser();
        }
        else{
            if (Tkt_User::isvalid() && Tkt_User::issupport()) {
                $this->redirect("/admin");
            }
        }
        
        $this->_helper->layout->setLayout('adminlayout');
    }

    public function loginAction() {
        $this->_helper->layout->disableLayout();
        $this->view->login_error = false;

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $clave = sha1($_POST['password']);

            $mod_user = new Mod_Users();
            $user = $mod_user->fetchRow("email='{$_POST['email']}' AND password='$clave'");

            if ($user) {
                Tkt_User::setUser($_POST['email'], true);
                $this->view->user=Tkt_User::getUser();
                $this->redirect("/admin/?tktid=&tktstatus=1&tktpriority=0");
            }
            $this->view->login_error = true;
        }
    }

    public function indexAction() {
        $this->view->selected_status_1 = "";
        $this->view->selected_status_2 = "";
        $this->view->selected_status_3 = "";
        $this->view->selected_status_4 = "";
        $this->view->selected_status_5 = "";
        
        $this->selected_priority_1 = $this->selected_priority_2 = $this->selected_priority_3 = $this->selected_priority_4 = "";
        
        $uri="/admin?";
        
        
        $where = "true ";

        if (isset($_GET['tktid']) && $_GET['tktid'] > 0){
            $where .= ' AND id = ' . $_GET['tktid'];
        $uri=$uri.'tktid='.$_GET['tktid'];
            
        }
        if (isset($_GET['tktstatus']) && $_GET['tktstatus'] > 0) {
            $where .= ' AND status = ' . $_GET['tktstatus'];
            $nombre = "selected_status_" . $_GET['tktstatus'];
            $this->view->$nombre = 'selected="selected"';
            $uri=$uri.'&tktstatus='.$_GET['tktstatus'];
        }

        if (isset($_GET['tktpriority']) && $_GET['tktpriority'] > 0) {
            $where .= ' AND priority = ' . $_GET['tktpriority'];
            $nombre = "selected_priority_" . $_GET['tktpriority'];
            $this->view->$nombre = 'selected="selected"';
            $uri=$uri.'&tktpriority='.$_GET['tktpriority'];
        }
        
        if (isset($_GET['tkthelptopic']) && $_GET['tkthelptopic'] > 0){
            $where .= ' AND id_helptopic = ' . $_GET['tkthelptopic'];
            $uri=$uri.'&tkthelptopic='.$_GET['tkthelptopic'];
        }
        
        $uri_pager=$uri;
        
        $this->view->selectedpage = 1;
        if (isset($_GET['page']) && $_GET['page'] > 0){
            $this->view->selectedpage = $_GET['page'];
            $uri=$uri.'&page='.$_GET['page'];
        }
        
        
        $mod_tickets = new Mod_Ticket();
        $countitems= $mod_tickets->getCount($where);
        /*PAGINADO*/
        $this->view->cantpaginas = ceil($countitems/10);
        //$tickets = $mod_tickets->fetchAll($where);
        $tickets = $mod_tickets->getTktLimitOrder($where, 10,($this->view->selectedpage-1)*10,"created_date DESC" );
        $this->view->tickets = $tickets;
        
        
        if ($this->view->selectedpage == 1){
        $this->view->prev="#";
        $this->view->first="#";
        }  else{
            $this->view->prev=$uri_pager."&page=".($this->view->selectedpage-1);
            $this->view->first=$uri_pager."&page=1";
        }
        
        if ($this->view->selectedpage == $this->view->cantpaginas){
        $this->view->next="#";
        $this->view->last="#";
        }else{
            $this->view->next=$uri_pager."&page=".($this->view->selectedpage+1);
            $this->view->last=$uri_pager."&page=".$this->view->cantpaginas;
        }
        
        
        /*FIN PAGINADO*/
        
        
        
        $mod_helptopic = new Mod_HelpTopic();
        
        if( isset($_GET['tkthelptopic']) )
            $this->view->selectHelpTopic = $_GET['tkthelptopic'];
        else {
            $this->view->selectHelpTopic = 0;
        }
        $this->view->uri=$uri;
        $this->view->helptopics = $mod_helptopic->fetchAll();//$helptopics_options;
        
    }
    
    
    public function gethelptopicAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        
    }
    
    
    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        Tkt_User::unsetUser();

        $this->redirect("/");
    }

    public function gettktAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $id = $this->getParam('id');

        $mod_ticket = new Mod_Ticket();
        $tkt = $mod_ticket->fetchRow("id = $id");

        $mod_helptopic = new Mod_HelpTopic();
        $helptopic = $mod_helptopic->fetchRow("id=" . $tkt->id_helptopic);

        $_data = $tkt->toArray();
        $status = $_data['status'];
        $priority = $_data['priority'];

        $_data['description'] = nl2br($_data['description']);
        $_data['priority'] = Mod_Priority::getPriorityName($priority);
        $_data['priorityclass'] = Mod_Priority::getPriorityViewClass($priority);
        $_data['priorityvalue'] = $priority;
        $_data['status'] = Mod_Status::getStatusName($status);
        $_data['statusclass'] = Mod_Status::getStatusViewClass($status);
        $_data['statusvalue'] = $status;
        $_data['attachedlink'] = '/upload/' . $_data['attached'];

        $_data['helptopic'] = '';
        if ($helptopic) {
            $_data['helptopic'] = $helptopic->title;
        }

        $mod_ticketcomment = new Mod_TicketComment();
        $comments = $mod_ticketcomment->fetchAll("id_ticket = $id");

        $_data['comments'] = $comments->toArray();

        for ($i = 0; $i < count($_data['comments']); $i++) {
            $_data['comments'][$i]['comment'] = nl2br($_data['comments'][$i]['comment']);
        }

        echo json_encode($_data);
        exit;
    }

    public function helptopicAction() {

        $mod_helptopic = new Mod_HelpTopic();

        $this->view->helptopics = $mod_helptopic->fetchAll();
    }

    public function addhelptopicAction() {

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (isset($_POST['helptopic']) && !empty($_POST['helptopic'])) {


            $mod_helptopic = new Mod_HelpTopic();
            $ht = $mod_helptopic->createRow();
            $ht->title = $_POST['helptopic'];
            $ht->save();
        }
        $this->redirect("/admin/helptopic");
    }

    public function deletehelptopicAction() {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $mod_helptopic = new Mod_HelpTopic();
            $ht = $mod_helptopic->fetchRow("id=" . $_GET['id']);
            if ($ht){
                try{
                    $ht->delete();
                    }
                    catch(Exception $e){
                        echo $e->getMessage();
                        exit;
                    }
                
            }
        }
        $this->redirect("/admin/helptopic");
    }

    public function adduserAction() {
        $this->view->user_exists=false;
        $this->view->pass_mismatch=false;
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordconfirm'])) {
            if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordconfirm'])) {
                $mod_user = new Mod_Users();                
                $user = $mod_user->fetchRow("email='{$_POST['email']}'");
                if ($user) {
                    $this->view->user_exists=true;
                } elseif (($_POST['password']) != ($_POST['passwordconfirm'])) {
                        $this->view->pass_mismatch=true;
                    } else {
                            $mod_user = new Mod_Users();
                            $newrow=$mod_user->createRow();
                            $newrow->email=$_POST['email'];
                            $newrow->password=sha1($_POST['password']);
                            $newrow->save();
                            $this->redirect("/admin/?tktid=&tktstatus=1&tktpriority=0");
                    }
            }
        }
    }

    public function savetktAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (isset($_POST['editFrmTktId']) && $_POST['editFrmTktId'] > 0) {

            $mod_ticket = new Mod_Ticket();

            $tkt = $mod_ticket->fetchRow("id=" . $_POST['editFrmTktId']);

            if ($tkt) {

                $tkt->priority = $_POST['editFrmTktPriority'];
                $tkt->status = $_POST['editFrmTktStatus'];
                $tkt->updated_date = date("Y-m-d h:i:s");
                $tkt->support_user = Tkt_User::getUser();

                $tkt->id_helptopic = $_POST['editFrmTktHelpTopic'];

                $tkt->save();

                if (!empty($_POST['editFrmTktComment'])) {

                    $mod_ticketcomment = new Mod_TicketComment();
                    $comment = $mod_ticketcomment->createRow();

                    $comment->id_ticket = $_POST['editFrmTktId'];
                    $comment->comment = $_POST['editFrmTktComment'];
                    $comment->created_date = date("Y-m-d h:i:s");
                    $comment->author = Tkt_User::getUser();
                    $comment->is_support_user = 1;

                    $comment->save();
                }

                $_confini = Common_Config::getInstance();

                $config = array(
                    'auth' => 'login',
                    'username' => $_confini->email->smtp->username,
                    'password' => $_confini->email->smtp->password,
                    'port' => $_confini->email->smtp->port,
                    'ssl' => $_confini->email->smtp->ssl
                );

                $transport = new Zend_Mail_Transport_Smtp($_confini->email->smtp->host, $config);

                $mail = new Zend_Mail('UTF-8');
                $mail->setBodyText('Updated ticket [tkt #' . $tkt->id . ']: ' . $comment->comment);
                $mail->setBodyHtml('<h1>Updated ticket:  #' . $tkt->id . "</h1>" . "<h3>By: " . Tkt_User::getUser() . "</h3>" . nl2br($comment->comment));
                $mail->setFrom('buzz.support@avatarla.com', 'Buzz Support');
                $mail->setReplyTo('buzz.support@avatarla.com', 'Buzz Support');
                $mail->addTo($tkt->created_user);
                $mail->setSubject('Updated ticket [tkt #' . $tkt->id . '] ');
                try {
                    $mail->send($transport);
                } catch (Exception $e) {
                    throw new Exception("We can't email the ticket", 1002);
                }
            }
        }
        
        //$this->redirect("/admin/?tktid=&tktstatus=1&tktpriority=0");
       
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

   /* public function tktsautoAction(){
        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $mod_tickets = new Mod_Ticket();
        
        for($i=0; $i <100; $i++){
            
            $tkt = $mod_tickets->createRow();
            
            $tkt->priority = rand(1, 4);
            $tkt->status = rand(1,5);
            
            $tkt->title         = "Ticket de prueba - $i";
            $tkt->description   = "Ticket de prueba - $i";
            $tkt->id_helptopic  = 1;
            
            $tkt->created_date  = date("Y-m-d h:i:s");
            $tkt->updated_date  = date("Y-m-d h:i:s");
            $tkt->created_user  = Tkt_User::getUser();
            
            $tkt->save();
            
        }
        
    }*/
    
}

?>
