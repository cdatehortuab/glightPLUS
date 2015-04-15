<?php

/**
 * Project:     Framework G - G Light
 * File:        super_controller.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */

class super_controller 
{
	//vars
	var $engine; //template engine
	var $gvar; //links - global vars
	var $type_warning; //type warning (allowed: information - success - error - alerts)
	var $msg_warning; //message warning
	var $error; //activator error
	var $get; //GET
	var $post; //POST
	var $files; //FILES
	var $session; //SESSION
	var $server; //SERVER
	
	var $temp_aux; //template auxiliar 
	var $temp_aux2; //template auxiliar 
	var $temp_aux3; //template auxiliar 
	var $orm; //object to relational
	
	public function __construct()
	{
		$this->set_gvar_and_engine();
		
		$this->type_warning="error";
		$this->msg_warning="";
		$this->error=0;
		
		$this->get=$_GET; settype($this->get,'object');
		$this->post=$_POST; settype($this->post,'object');
		$this->files=$_FILES; settype($this->files,'object');
		$this->session=$_SESSION; //session is not object, because is not recomended manage objects in sessions
		$this->server=$_SERVER;

		$this->temp_aux='empty.tpl';
		$this->temp_aux2='empty.tpl';
		$this->temp_aux3='empty.tpl';
		
		$this->orm = new orm();
	}
	
	private function set_gvar_and_engine()
	{
		require(C_FULL_PATH."modules/m_smarty/Smarty.class.php"); //smarty
		require(C_FULL_PATH."configs/gvar.php"); //global vars
		$this->engine = new Smarty; //create new smarty object
		$this->gvar = $gvar; // set gvar

		//smarty configuration
		$this->engine->template_dir = C_FULL_PATH."templates";
		$this->engine->config_dir = C_FULL_PATH."configs";
		$this->engine->cache_dir = C_FULL_PATH."cache";
		$this->engine->compile_dir = C_FULL_PATH."templates_c";
		//end smarty configuration
		
		$this->engine->assign('gvar',$gvar); //assign vars
	}
}

?>