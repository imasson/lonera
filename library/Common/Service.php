<?php
class Common_Service
{
    private $_error_code = 0;
    private $_error_message = "";
    
    protected function responseError($code, $message)
    {
        $this->_error_code = $code;
        $this->_error_message = $message;
        
        return $this->response(array());
    }
    
    /**
     *
     * @param array $data
     * @return Common_Service_Response 
     */
    protected function response(array $data)
    {
        return new Common_Service_Response($data, $this->_error_code, $this->_error_message);
    }
}
?>
