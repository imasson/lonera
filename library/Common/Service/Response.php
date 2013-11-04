<?php
class Common_Service_Response
{
    private $_error_code = 0;
    private $_error_message = "";
    private $_data = array();
    
    const TRANSPORT_JSON = 'JSON';
    const TRANSPORT_XML = 'XML';
    
    public function __construct($_data, $_error_code=0, $_error_message="") 
    {
        $this->_data = $_data;
        $this->_error_code = $_error_code;
        $this->_error_message = $_error_message;
    }
    
    public function __toArray()
    {
        $_response = array(
            "head"=>array("error_code" => $this->_error_code, "error_message" => $this->_error_message),
            "body"=> $this->_data
        );
        
        return $_response;
    }
    
    /**
     * @todo TRANSPORT_XML contiene un bug, no genera bien el XML
     * @param string $_format
     * @return String
     */
    public function __getTransport( $_format = self::TRANSPORT_JSON )
    {
        switch ($_format) {
            case self::TRANSPORT_JSON:
                return json_encode( $this->__toArray() ) ;
                break;
            
            case self::TRANSPORT_XML:
                //Tiene errores
                $xml = new SimpleXMLElement('<root/>');
                $_arrXml = $this->__toArray();
                array_walk_recursive($_arrXml, array($xml, 'addChild'));
                return $xml->asXML();
                break;
            
            default:
                return json_encode( $this->__toArray() ) ;
                break;
        }
        
    }
    
    public function __sendResponse( $_format = self::TRANSPORT_JSON )
    {
        header('Content-type: application/json');
        echo $this->__getTransport( $_format );
    }
    
    
    public function hasError()
    {
        if( $this->_error_code > 0 )
            return true;
        else
            return false;
    }
    
    public function getErrorMessage()
    {
        return $this->_error_message;
    }
    
    public function getErrorCode()
    {
        return $this->_error_code;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    public function setData($k,$v)
    {
        return $this->_data[$k]=$v;
    }
}
?>