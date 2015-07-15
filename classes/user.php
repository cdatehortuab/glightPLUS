<?php
 
class user extends object_standard
{
	//attributes
	protected $id;
	protected $name;
	protected $user;
	protected $password;
	protected $type;
	protected $email;
	
	//data about the attributes
	public static function metadata()
	{
		return array("id" => array(), "name" => array(), "user" => array(), "password" => array(), "type" => array(), "email" => array()); 
	}

	public static function primary_key()
	{
		return array("id");
	}
	
	public static function relational_keys($class, $rel_name)
	{
		switch($class)
		{		
		    default:
			break;
		}
	}
}

?>