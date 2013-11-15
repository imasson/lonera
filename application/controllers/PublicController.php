<?php

class PublicController extends Zend_Controller_Action {

    public function init() {
        if (!Tkt_User::isvalid()) {
            $this->redirect("/");
        }
    }

    public function indexAction() {
        $this->view->body_class = "";
        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $this->redirect("/public/list");
        
    }

    public function listAction() {
        
        $this->view->selected_status_1 = "";
        $this->view->selected_status_2 = "";
        $this->view->selected_status_3 = "";
        $this->view->selected_status_4 = "";
        $this->view->selected_status_5 = "";
        
        $this->selected_priority_1 = $this->selected_priority_2 = $this->selected_priority_3 = $this->selected_priority_4 = "";
        
        $where = "created_user='".Tkt_User::getUser()."'";
        
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

    public function newAction() {

        $mod_helptopic = new Mod_HelpTopic();

        $this->view->helptopics = $mod_helptopic->fetchAll();
    }

    public function addAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (isset($_POST['tktTitle']) && !empty($_POST['tktTitle'])) { 
            // ["tktTitle"]=> string(15) "asdasdasdasdasd" ["tktDescription"]=> string(0) "" ["tktHelpTopic"]=> string(1) "0" ["tktPriority"]=> string(1) "0" 
            $mod_ticket = new Mod_Ticket();

            $tkt = $mod_ticket->createRow();

            $tkt->title         = $_POST['tktTitle'];
            $tkt->description   = $_POST['tktDescription'];
            $tkt->id_helptopic  = $_POST['tktHelpTopic'];
            //$tkt->status        = $_POST['']; DEFAULT 1
            $tkt->created_date  = date("Y-m-d h:i:s");
            $tkt->updated_date  = date("Y-m-d h:i:s");
            $tkt->created_user  = Tkt_User::getUser();
            
            if($_POST['tktPriority'] > 0)
                $tkt->priority  = $_POST['tktPriority'];
            
            $id_ticket = $tkt->save();
            
            
            $_confini = Common_Config::getInstance();
                
            $config = array(
                'auth'      => 'login',
                'username'  => $_confini->email->smtp->username,
                'password'  => $_confini->email->smtp->password,
                'port'      => $_confini->email->smtp->port,
                'ssl'       => $_confini->email->smtp->ssl
            );

            $transport = new Zend_Mail_Transport_Smtp($_confini->email->smtp->host, $config);
            
            $accounts = explode(";", $_confini->email->support->accounts);
            
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyText('New ticket added:  #'.$id_ticket);
            $mail->setBodyHtml('<h1>New ticket added:  #'.$id_ticket."</h1>"."<h3>By: ". Tkt_User::getUser()."</h3>".  nl2br($tkt->description));
            $mail->setFrom('buzz.support@avatarla.com', 'Buzz Support');
            $mail->setReplyTo('buzz.support@avatarla.com', 'Buzz Support');
            
            foreach ($accounts as $account){
                $mail->addTo($account);
            }
            $mail->setSubject('TKT - '.Mod_Priority::getPriorityName($tkt->priority).' - '.$tkt->title );
            try{
                $mail->send($transport);
            }catch (Exception $e){
                throw new Exception("We can't email the ticket", 1002);
            }
            
            $this->redirect("/public/list");
            
        } else {
            $this->redirect("/public/new");
        }
    }
    
    public function gettktAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $id = $this->getParam('id');
        
        $mod_ticket = new Mod_Ticket();
        $tkt = $mod_ticket->fetchRow("id = $id");
        
        $mod_helptopic = new Mod_HelpTopic();
        $helptopic = $mod_helptopic->fetchRow("id=".$tkt->id_helptopic);
        
        $_data = $tkt->toArray();
        $status     = $_data['status'];
        $priority   = $_data['priority'];
        
        $_data['description'] = nl2br($_data['description']);
        $_data['priority'] = Mod_Priority::getPriorityName($priority);
        $_data['priorityclass'] = Mod_Priority::getPriorityViewClass($priority);
        $_data['status'] = Mod_Status::getStatusName($status);
        $_data['statusclass'] = Mod_Status::getStatusViewClass($status);
        
        $_data['helptopic'] = '';
        if( $helptopic ){
            $_data['helptopic'] = $helptopic->title;
        }
        
        $mod_ticketcomment = new Mod_TicketComment();
        $comments = $mod_ticketcomment->fetchAll("id_ticket = $id");
        
        $_data['comments'] = $comments->toArray();
        
        for($i=0; $i < count($_data['comments']); $i++ ){
            $_data['comments'][$i]['comment'] = nl2br($_data['comments'][$i]['comment']);
        }
        
        echo json_encode( $_data );
        exit;
    }
    
    public function savecommentAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        if (isset($_POST['editFrmTktId']) && $_POST['editFrmTktId'] > 0 && !empty($_POST['editFrmTktComment'])  ) {
            
            $mod_ticketcomment = new Mod_TicketComment();
            
            $comment = $mod_ticketcomment->createRow();
            
            $comment->id_ticket = $_POST['editFrmTktId'];
            $comment->comment = $_POST['editFrmTktComment'];
            $comment->created_date = date("Y-m-d h:i:s");
            $comment->author  = Tkt_User::getUser();
            
            $comment->save();
            
            $_confini = Common_Config::getInstance();
                
            $config = array(
                'auth'      => 'login',
                'username'  => $_confini->email->smtp->username,
                'password'  => $_confini->email->smtp->password,
                'port'      => $_confini->email->smtp->port,
                'ssl'       => $_confini->email->smtp->ssl
            );

            $transport = new Zend_Mail_Transport_Smtp($_confini->email->smtp->host, $config);
            
            $accounts = explode(";", $_confini->email->support->accounts);
            
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyText('New comment added [tkt #'.$comment->id_ticket.']: '.$comment->comment);
            $mail->setBodyHtml('<h1>New comment added to ticket:  #'.$comment->id_ticket."</h1>"."<h3>By: ". Tkt_User::getUser()."</h3>".  nl2br($comment->comment));
            $mail->setFrom('buzz.support@avatarla.com', 'Buzz Support');
            $mail->setReplyTo('buzz.support@avatarla.com', 'Buzz Support');
            
            foreach ($accounts as $account){
                $mail->addTo($account);
            }
            $mail->setSubject('New comment added [tkt #'.$comment->id_ticket.'] ' );
            try{
                $mail->send($transport);
            }catch (Exception $e){
                throw new Exception("We can't email the ticket", 1002);
            }
            
        }
        
        $this->redirect("/public/list");
    }

}

?>