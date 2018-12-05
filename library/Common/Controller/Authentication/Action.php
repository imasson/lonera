<?php
class Common_Controller_Authentication_Action extends Common_Controller_Action 
{
    public function init()
    {
        parent::init();
        //Common_User::getInstance()->logout();
        if( !Common_User::getInstance()->isValid() )
        {
            $this->redirect("/user/login");
        }
        $layout = Zend_Layout::getMvcInstance();
        $layout->fullusername = Common_User::getInstance()->getFullUserName();
        $layout->userid = Common_User::getInstance()->getId();
        $credentials = Common_User::getInstance()->getCredentials();
        $layout->username = $credentials["username"];
        
        $layout->FLAT_VERSION_JS = Common_Config::getInstance()->flat->version->system;
        
        //Project data.
        
        if( (int)$this->_getParam("p") > 0  )
        {
            $_id_project = $this->_getParam("p");
            
            $_mod_project = new Mod_Project();
            $_projects = $_mod_project->getProjectsByUser( Common_User::getInstance()->getId(), $_id_project );
            
            if( $_projects !== null )
            {
                //set first project on the rowset as default project 
                $_projects->rewind();
                Common_Project::getInstance()->setValid( $_projects->current()->toArray() );      
            }
            
            //change to project in session and redirect for get a nice url
            $this->redirect("/tool");
        }
        
        
        //If no set a project, select one.
        if( !Common_Project::getInstance()->isValid() )
        {   
            $_mod_project = new Mod_Project();
            $_projects = $_mod_project->getProjectsByUser( Common_User::getInstance()->getId() );
            
            if( $_projects !== null )
            {
                //set first project on the rowset as default project 
                $_projects->rewind();
                Common_Project::getInstance()->setValid( $_projects->current()->toArray() );
            }else{
                //If the user hasn't projects, set the support project
                $_project = array("id"=>0, "title"=>"Einstein Support", "folder"=>"einstein");
                Common_Project::getInstance()->setValid($_project);
            }
        }
        
        $layout->project_id = Common_Project::getInstance()->getId();
        $layout->project_name = Common_Project::getInstance()->getName();
        $layout->project_folder = Common_Project::getInstance()->getFolder(); 
        //$layout->project_markets = json_encode( array( array( "value"=>"CO","label"=>"Colombia" ), array( "value"=>"AR","label"=>"Argentina" ) ) );
        $layout->project_markets = json_encode( Common_Project::getInstance()->getConfig("markets") );
        
        
        $filters = Flat_Filter::getInstance()->getDocumentListFilters();
        $layout->filter = $filters;
        
    }
    
    
}
?>