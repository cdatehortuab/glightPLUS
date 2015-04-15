<?php

/**
 * Project:     Framework G - G Light
 * File:        functions.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */
 
//check if a var is empty
function is_empty($data)
{
	if(!isset($data) || ($data == NULL)){return TRUE;}
	else{return FALSE;}
}

//load all files from /classes when is required 
function autoload($class_name) 
{
	if(is_file(C_FULL_PATH."classes/".$class_name.".php"))
	{include_once(C_FULL_PATH."classes/".$class_name.".php");}
}

//throw exception to catch zone
function throw_exception($message)
{
	throw new Exception($message);
}

//function for programmers, for printing data in tree form (more easy to see)
function print_r2($val)
{
	echo '<pre><font color="red"><b>'; print_r($val); echo '</b></font></pre>';
}

spl_autoload_register("autoload"); //autoloader classes

?>