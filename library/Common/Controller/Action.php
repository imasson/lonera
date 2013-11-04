<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Common_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
    }
    
    public function disableAllViews()
    {
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout->disableLayout();
    }

    public function disableView()
    {
            $this->_helper->viewRenderer->setNoRender();
    }

    public function disableLayout()
    {
            $this->_helper->layout->disableLayout();
    }
    
}
?>
