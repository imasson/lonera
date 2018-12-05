<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Log
 *
 * @author Sebastian
 */
class Common_Log
{
    /**
     *
     * @return  Zend_Log
     */
    public static function getInstance()
    {
        return Zend_Registry::get('_COMMON_LOG_');
    }

    public static function createLog( Zend_Log $log )
    {
        if( ! Zend_Registry::isRegistered('_COMMON_LOG_') )
        {
            Zend_Registry::set('_COMMON_LOG_', $log );
        }
    }

    public static function destroyLog()
    {
        Zend_Registry::set('_COMMON_LOG_', null );
    }
}
?>
