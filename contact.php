<?php

require('configs/include.php');

class c_contact extends super_controller {

	public function display()
	{		
		$this->engine->assign('title',$this->gvar['contact']['name']);
		$this->engine->assign('active',$this->gvar['contact']['name']);
		$this->engine->assign('where', 'contact');
		
		$this->engine->display('header.tpl');
		$this->engine->display('contact.tpl');
		$this->engine->display('footer.tpl');
	}
	
	public function run()
	{
		$this->display();
	}
}

$call = new c_contact();
$call->run();

?>