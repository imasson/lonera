<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->view->not_is_valid_user = 'false';
        if (!Tkt_User::isvalid()) {
            $this->view->not_is_valid_user = 'true';
        }
        else
            $this->view->user = Tkt_User::getUser();

        if (Tkt_User::issupport()) {
            $this->redirect("/admin");
        }
    }

    public function indexAction() {
        $this->view->body_class = "bs-docs-home";
    }

    public function loginAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if (filter_var($_POST['userLoginEmail'], FILTER_VALIDATE_EMAIL)) {
            Tkt_User::setUser($_POST['userLoginEmail']);
        }

        $this->redirect("/");
    }

    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        Tkt_User::unsetUser();

        $this->redirect("/");
    }

    public function hashAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        echo sha1($_GET['clave']);
    }

}

?>