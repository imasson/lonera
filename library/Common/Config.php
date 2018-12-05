<?php

class Common_Config 
{   
    private static $instance;
    
    private function __constructor()
    {
        
    }
    
    /**
     * 
     * @return Zend_Config_Ini
     */
    public static function getInstance()
    {
        if( !self::$instance instanceof Zend_Config_Ini)
        {
            self::$instance = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini',APPLICATION_ENV);
        }
        
        return self::$instance;
    }
}
?>
