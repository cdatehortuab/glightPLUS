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
	var $where; //Name of the page
	var $lang; //Language to display the page
	var $engine; //template engine
	var $gvar; //links - global vars
	var $type_warning; //type warning (allowed: info- success - danger - warning) 
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
	var $main_content; 
	var $orm; //object to relational
	
	public function __construct()
	{
		$this->where = substr(get_class($this), 2);
		
		$this->set_gvar_and_engine();
		
		$this->type_warning="danger";
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
		$this->main_content = $this->where.".tpl";
		
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
		
		$this->lang = $this->gvar['lang'][0];
		
		$this->engine->assign('gvar',$gvar); //assign vars
		$this->engine->assign('where', $this->where);
		$this->engine->assign('title', $this->gvar[$this->where]['name'][$this->lang]." | ".$this->gvar['n_global']);
		$this->engine->assign('active', $this->gvar[$this->where]['link']);
		$this->engine->assign('lang', $this->lang);
	}
	
	public function run() {
		try {
			if (isset($this->get->option)) {
				if (method_exists($this, $this->get->option))
					$this->{$this->get->option}();
				else
					throw_exception($this->gvar['m_unavailable_option'][$this->lang]);
			}
		} catch (Exception $e) {
			$this->error = 1;
			$this->msg_warning = $e->getMessage();
			$this->temp_aux = 'message.tpl';
			$this->engine->assign('type_warning',$this->type_warning);
			$this->engine->assign('msg_warning',$this->msg_warning);
		}
		$this->display();
	}
	
	public function display()
	{
		$this->engine->display('header.tpl');
		$this->engine->display($this->temp_aux);
		$this->engine->display($this->main_content);
		$this->engine->display('footer.tpl');
	}
}

?>