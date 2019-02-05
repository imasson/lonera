<?php

class ProductController extends Zend_Controller_Action {

    public function init() {
        if (!Tkt_User::isvalid()) {
            $this->redirect("/");
        }

        if (Tkt_User::issupport()) {
            $this->redirect("/admin");
        }
        $this->view->user = Tkt_User::getUser();
    }

    public function indexAction() {
        $this->view->body_class = "";

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $this->redirect("/public/list");
    }

    public function listAction() {
        $where = 'id > 0 ';
        if (isset($_GET['client_id']) && $_GET['client_id'] > 0)
            $where .= 'id = ' . $_GET['client_id'];

        $order = new Product();
        $products = $order->getAllProducts($where, '');

        $this->view->products = $products;
    }



    public function newAction() {


    }

    public function addAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * array(1) { ["tktfile"]=> array(5) { ["name"]=> string(24) "star-trek-2_1-single.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(23) "C:\xampp\tmp\php6DC.tmp" ["error"]=> int(0) ["size"]=> int(47365) } }
         */

        if (isset($_POST['name']) && !empty($_POST['name'])) {


            // ["tktTitle"]=> string(15) "asdasdasdasdasd" ["tktDescription"]=> string(0) "" ["tktHelpTopic"]=> string(1) "0" ["tktPriority"]=> string(1) "0" 
            $prod = new Product();

            $prod = $prod->createRow();

            $prod->name = $_POST['name'];
            $prod->color = $_POST['color'];
            $prod->description = $_POST['description'];

            $id = $prod->save();


            $this->redirect("/product/list");
        } else {
            $this->redirect("/product/new");
        }
    }

    public function getclientAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $id = $this->getParam('id');

        $mod_ticket = new Client();
        $tkt = $mod_ticket->fetchRow("id = $id");

        //$mod_helptopic = new Mod_HelpTopic();
        //$helptopic = $mod_helptopic->fetchRow("id=" . $tkt->id_helptopic);

        $_data = $tkt->toArray();
        /*$status = $_data['status'];
        $priority = $_data['priority'];

        $_data['description'] = nl2br($_data['description']);
        $_data['priority'] = Mod_Priority::getPriorityName($priority);
        $_data['priorityclass'] = Mod_Priority::getPriorityViewClass($priority);
        $_data['status'] = Mod_Status::getStatusName($status);
        $_data['statusclass'] = Mod_Status::getStatusViewClass($status);
        
        if (isset($_data['attached'])) {
            $files = explode(",", $_data['attached']);
            $_data['attachedlink'] = $files;
        }
        
        
        $_data['helptopic'] = '';
        if ($helptopic) {
            $_data['helptopic'] = $helptopic->title;
        }

        $mod_ticketcomment = new Mod_TicketComment();
        $comments = $mod_ticketcomment->fetchAll("id_ticket = $id");

        $_data['comments'] = $comments->toArray();

        for ($i = 0; $i < count($_data['comments']); $i++) {
            $_data['comments'][$i]['comment'] = nl2br($_data['comments'][$i]['comment']);
        }*/

        echo json_encode($_data);
        exit;
    }

    public function savecommentAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (isset($_POST['editFrmTktId']) && $_POST['editFrmTktId'] > 0 && !empty($_POST['editFrmTktComment'])) {

            $mod_ticketcomment = new Mod_TicketComment();

            $comment = $mod_ticketcomment->createRow();

            $comment->id_ticket = $_POST['editFrmTktId'];
            $comment->comment = $_POST['editFrmTktComment'];
            $comment->created_date = date("Y-m-d h:i:s");
            $comment->author = Tkt_User::getUser();

            $comment->save();

            $mod_tkt_files = new Mod_Ticket();
            $tkt_f = $mod_tkt_files->fetchRow("id=$comment->id_ticket");


            for ($i = 1; $i < count($_FILES); $i++) {
                if (isset($_FILES['tktfile' . $i]) && $_FILES['tktfile' . $i]['error'] == 0 && $_FILES['tktfile' . $i]['size'] > 0) {
                    $config = Common_Config::getInstance();

                    $tmp_filename = $_FILES['tktfile' . $i]['tmp_name'];

                    $destination = $config->tkttool->upload->dir;

                    $filename = $_POST['editFrmTktId'] . $_FILES['tktfile' . $i]['name'];
                    //$filesystem_name = sha1($filename);

                    if (move_uploaded_file($tmp_filename, $destination . $filename)) {

                        if ($tkt_f->attached !== null) {
                            $tkt_f->attached = $tkt_f->attached . "," . $filename;
                        } else {
                            $tkt_f->attached = $filename;
                        }
                        $id_ticket = $tkt_f->save();
                    } else {
                        //echo "NOOO";
                    }
                }
            }



            $mod_tkt = new Mod_Ticket();
            $subject = $mod_tkt->fetchRow("id=$comment->id_ticket");

            $_confini = Common_Config::getInstance();

            $config = array(
                'auth' => 'login',
                'username' => $_confini->email->smtp->username,
                'password' => $_confini->email->smtp->password,
                'port' => $_confini->email->smtp->port,
                'ssl' => $_confini->email->smtp->ssl
            );

            $transport = new Zend_Mail_Transport_Smtp($_confini->email->smtp->host, $config);

            //$accounts = explode(";", $_confini->email->support->accounts);

            $mail = new Zend_Mail('UTF-8');



            $mail->setBodyText('New comment added [tkt #' . $comment->id_ticket . ']: ' . $comment->comment . " Subject: " . $subject->title);
            $mail->setBodyHtml('<h1>New comment added to ticket:  #' . $comment->id_ticket . "</h1>" . "<h2>Subject: " . $subject->title . "</h2>" . "<a href=http://tkttool.emmett.avatarlahs.com.ar/admin/?tktid=" . $comment->id_ticket . "&autoedit=1> <h2>Click to edit ticket</h2></a>" . "<h3>By: " . Tkt_User::getUser() . "</h3>" . nl2br($comment->comment));
            $mail->setFrom('buzz.support@avatarla.com', 'Buzz Support');
            $mail->setReplyTo('buzz.support@avatarla.com', 'Buzz Support');

            $mod_users = new Mod_Users();
            $users_accounts = $mod_users->fetchAll();
            for ($users_accounts->rewind(); $users_accounts->valid(); $users_accounts->next()) {
                $mail->addTo($users_accounts->current()->email);
            }


            $mail->setSubject('New comment added [tkt #' . $comment->id_ticket . '] ');
            try {
                $mail->send($transport);
            } catch (Exception $e) {
                throw new Exception("We can't email the ticket", 1002);
            }
        }

        $this->redirect("/public/list");
    }

}

?>