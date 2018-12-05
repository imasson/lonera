<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initLayout()
    {
        Zend_Layout::startMvc();
    }
    
    /*protected function _initMiFUNCION(){}*/
    
    /**
    * Modo de uso desde un controlador:
    *      Common_Log::getInstance()->log("MENSAJE", Zend_Log::INFO);
    */
    protected function _initLog()
    {
        if ($this->hasPluginResource("log"))
        {
            $r = $this->getPluginResource("log");
            $log = $r->getLog();
            Common_Log::createLog($log);
        }
    }

}

