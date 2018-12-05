<?php

class Common_Project {
    
    const _NS_SESSION = "FLAT_NS_PROJECT";
    
    private static $instance = null;
    
    private function __constructor()
    {
        $_SESSION[self::_NS_SESSION] = new stdClass();
    }
    
    /**
     * @return Common_Project
     */
    public static function getInstance()
    {
        if( self::$instance === null )
        {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    public function setValid( array $_project)
    {
        if( !isset($_SESSION[self::_NS_SESSION]) )
            $_SESSION[self::_NS_SESSION] = new stdClass();
        
        $_SESSION[self::_NS_SESSION]->isValidSession = true;
        $_SESSION[self::_NS_SESSION]->projectdata = $_project;
    }
    
    public function destroy()
    {
        unset($_SESSION[self::_NS_SESSION]);
    }
    
    public function isValid()
    {
        if( isset($_SESSION[self::_NS_SESSION]->isValidSession) )
            return $_SESSION[self::_NS_SESSION]->isValidSession;
        
        return false;
    }
    
    
    public function getId()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return $_SESSION[self::_NS_SESSION]->projectdata["id"];
        }
        
        return false;   
    }
    
    public function getName()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return $_SESSION[self::_NS_SESSION]->projectdata["title"];
        }
        
        return false;   
    }
    
    public function getFolder()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return $_SESSION[self::_NS_SESSION]->projectdata["folder"];
        }
        
        return false;   
    }
    
    public function getConfig($key=null)
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            if( $key === null ){
                return json_decode( $_SESSION[self::_NS_SESSION]->projectdata["config"] );
            }
            elseif( is_string($key) ){
                $_config = json_decode( $_SESSION[self::_NS_SESSION]->projectdata["config"],true);
                if( isset( $_config[$key] ) )
                    return $_config[$key];
            }
                
        }
        
        return false;   
    }
    
    
}
?>
