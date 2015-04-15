<?php

/**
 * Project:     Framework G - G Light
 * File:        orm.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */

class orm
{
	var $db;
	var $data;
	var $objects_to_return;
	var $numpages;
	var $last_id;
	
	public function orm(){}
	
	//conect to db
	public function connect()
	{
		$this->db = new db();
		$this->db->connect();
	}
	
	//extract data from db
	public function read_data($class, $options, $cod = array())
	{	
		for ($i=0,$n=count($class); $i<$n; $i++)		
		{
			$options["$class[$i]"]['lvl1']=$class[$i];
			$this->data["$class[$i]"] = $this->db->select($options["$class[$i]"],$cod["$class[$i]"]);
			$this->numpages["$class[$i]"] = $this->db->numpages;
		}
	}
	
	//transform data in respective objects
	public function get_objects($class, $components = NULL, $auxiliars = NULL)
	{
		unset($this->objects_to_return);
		$pos=0;
		$data = $this->get_data($class,$pos);
		while($data != NULL)
		{
			$this->objects_to_return[] = new $class($data, $components, $this, $auxiliars);
			$pos++;
			$data = $this->get_data($class,$pos);
		}
		return $this->objects_to_return;
	}
	
	//return data
	public function get_data($class,$pos)
	{
		$data = $this->data[$class][$pos];
		return $data;
	}
	
	//insert data in db
	public function insert_data($lvl2, $object, $get_id = 'no')
	{
		$options['lvl1']=get_class($object);
		$options['lvl2']=$lvl2;
		if($get_id == 'no'){$this->db->insert($options,$object,$get_id);}
		else{$this->last_id[get_class($object)] = $this->db->insert($options,$object,$get_id);}
	}
	
	//update data from db
	public function update_data($lvl2, $object)
	{
		$options['lvl1']=get_class($object);
		$options['lvl2']=$lvl2;
		$this->db->update($options,$object);
	}
	
	//delete dara from db
	public function delete_data($lvl2, $object)
	{
		$options['lvl1']=get_class($object);
		$options['lvl2']=$lvl2;
		$this->db->delete($options,$object);
	}
	
	//return especific data from db
	public function optional_select($class, $options = array(), $cod = array())
	{
		$options[$class]['lvl1']=$class;
		return $this->db->select($options["$class"],$cod["$class"]);
	}
	
	//execute query or operation from db
	public function do_operation($query)
	{
		$this->db->do_operation($query);
	}
	
	//close connection to db
	public function close()
	{
		$this->db->close();
	}
}

?>