<?php

/**
 * Project:     Framework G - G Light
 * File:        db.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */

class db
{
    var $server = C_DB_SERVER; //DB server
	var $user = C_DB_USER; //DB user
    var $pass = C_DB_PASS; //DB password
	var $db = C_DB_DATABASE_NAME; //DB database name
	var $limit = C_DB_LIMIT; //DB limit of elements by page
	var $cn;
	var $numpages;
	
	public function db(){}
	
	//connect to database
	public function connect()
	{
		$this->cn = mysqli_connect($this->server, $this->user, $this->pass);
		if(!$this->cn) {die("Failed connection to the database: ".mysqli_error($this->cn));}
		if(!mysqli_select_db($this->cn,$this->db)) {die("Unable to communicate with the database $db: ".mysqli_error($this->cn));}
		mysqli_query($this->cn,"SET NAMES utf8");
	}
	
	//function for doing multiple queries
	public function do_operation($operation, $class = NULL)
	{
		$result = mysqli_query($this->cn, $operation) ;
		if(!$result) {$this->throw_sql_exception($class);}	
	}
	
	//function for obtain data from db in object form
	private function get_data($operation)
	{		
		$data = array(); 
		$result = mysqli_query($this->cn, $operation) or die(mysqli_error($this->cn));
		while ($row = mysqli_fetch_object($result))
		{
			array_push($data, $row);
		}
		return $data;
	}
	
	//throw exception to web document
	private function throw_sql_exception($class)
    {
		$errno = mysqli_errno($this->cn); $error = mysqli_error($this->cn);
		$msg = $error."<br /><br /><b>Error number:</b> ".$errno;
        throw new Exception($msg);
    }
	
	//for avoid sql injections, this functions cleans the variables
	private function escape_string(&$data)
	{
		if(is_object($data)) {
			foreach ($data->metadata() as $key => $attribute) {
				if (!is_empty($data->get($key))) {
					$data->set($key, mysqli_real_escape_string($this->cn,$data->get($key)));
				}
			}
			$this->escape_string($data->auxiliars);
			$this->escape_string($data->components);
		
		} else if(is_array($data)) {
			foreach ($data as $key => $value) {
				$this->escape_string($data[$key]);
			}
		} else {
			$data = mysqli_real_escape_string($this->cn, $data);
		}
	}
	
	//function for add data to db
	public function insert($options,$data) 
	{
		$this->escape_string($data);
		$query = $this->query_insert($options, $data);
		if ($query == NULL) {
			switch($options['lvl1'])
			{
				case "user":
				switch($options['lvl2'])
				{
					case "normal":
						//
						break;
				}
				break;
				
				default: break;
			}
		}
		$this->do_operation($query, $options['lvl1']);
	}
	
	//function for edit data from db
	public function update($options,$data) 
	{
		$this->escape_string($data);
		$query = $this->query_update($options, $data);
		if ($query == NULL) {
			switch($options['lvl1'])
			{
				case "user":
				switch($options['lvl2'])
				{
					case "normal":
						//
						break;
				}
				break;
				
				default: break;
			}
		}
		$this->do_operation($query, $options['lvl1']);
	}
	
	//function for delete data from db
	public function delete($options,$data)
	{
		$this->escape_string($data);
		$query = $this->query_delete($options, $data);
		if ($query == NULL) {
			switch($options['lvl1'])
			{
				case "user":
				switch($options['lvl2'])
				{
					case "normal": 
						//
						break;
				}
				break;
				
				default: break;			  
			}
		}
		$this->do_operation($query, $options['lvl1']);
	}
	
	//function that returns an array with data from a operation
	public function select($options,$data)
	{
		$info = array();
		$query = $this->query_select($options, $data);
		if ($query == NULL) {
			switch($options['lvl1'])
			{
				case "user":
				switch($options['lvl2'])
				{
					case "all": 
						//
						break;
				}
				break;
				
				default: break;
			}
		}
		$info = $this->get_data($query);
		return $info;
	}
	
	//close the db connection
	public function close()
	{
		if($this->cn){mysqli_close($this->cn);}
	}
	
	private function query_insert($options, $data) {
		
		function object_insert($object, &$metadata) {
			$obj_query = "(";
			$first = true;
			foreach ($metadata as $attribute => $value) {
				if (!$first)
					$obj_query .= ", ";

				$obj_query .= ($object->get($attribute) != NULL) ? "'{$object->get($attribute)}'" : "NULL";
				$first = false;
			}
			$obj_query .= ")";
			return $obj_query;
		}

		$class = $options['lvl1'];
		$lvl2 = $options['lvl2'];
		$metadata = $class::metadata();
		
		$query1 = "INSERT INTO {$class}(";
		$first = true;
		foreach ($metadata as $attribute => $value) {
			if (!$first)
				$query1 .= ", ";

			$query1 .= $attribute;
			$first = false;
		}
		$query1 .= ") VALUES ";

		switch ($lvl2) {
			case "normal":
				return $query1.object_insert($data, $metadata).";";
				break;

			case "multiples":
				$query2 = "";
				$first = true;
				foreach ($data as $object) {
					if (!$first) 
						$query2 .= ", ";

					$query2 .= object_insert($object, $metadata);
					$first = false;
				}
				return $query1.$query2.";";
				break;
			
			default:
				return NULL;
				break;
		}
	}
	
	private function query_update($options, $data) {
		$class = $options['lvl1'];
		$lvl2 = $options['lvl2'];
		$metadata = $class::metadata();
		
		switch ($lvl2) {
			case "normal":
				$query = "UPDATE {$class} SET ";
				$first = true;
				foreach ($metadata as $attribute => $value) {
					if ($data->get($attribute) != NULL) { 
						if (!$first)
							$query .= ', ';
						$query .= "{$attribute} = '{$data->get($attribute)}'";
						$first = false;
					}
				}

				$query .= " WHERE ";

				$first = true;
				foreach ($class::primary_key() as $attribute) {
					if (!$first)
						$query .= ' AND ';
					$query .= "{$attribute} = ".(($data->auxiliars[$attribute] != NULL) ? "'{$data->auxiliars[$attribute]}'" : "NULL");
					$first = false;
				}

				return $query.";";
				break;

			default:
				return NULL;
				break;
		}
		
	}
	
	private function query_delete($options, $data) {
		$class = $options['lvl1'];
		$lvl2 = $options['lvl2'];

		$query = "DELETE FROM {$class}";

		switch ($lvl2) {
			case "normal":
				$query .= " WHERE ";
				$pk = $class::primary_key();
				$first = true;
				foreach ($pk as $attribute) {
					if (!$first)
						$query .= " AND ";
					$query .= "{$attribute} = ".(($data->get($attribute) != NULL) ? "'{$data->get($attribute)}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;
			
			case "all":
				return $query.";";
				break;

			case "attributes":
				$attributes = $data->auxiliars['attributes'];
				$query .= " WHERE ";
				$first = true;
				foreach ($attributes as $attribute) {
					if (!$first)
						$query .= " AND ";
					$query .= "{$attribute} = ".(($data->get($attribute) != NULL) ? "'{$data->get($attribute)}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;

			case "foreign":
				$query .= " WHERE ";
				$foreign_object = $data->auxiliars['foreign_object'];
				$rel_name = $data->auxiliars['rel_name'];
				$foreign_class = get_class($foreign_object);
				$rk = $class::relational_keys($foreign_class, $rel_name);
				$metadata = $class::metadata();
				$first = true;
				foreach ($rk as $attribute) {
					if (!$first)
						$query .= " AND ";
					$foreign_attribute = $metadata[$attribute]['foreign_attribute'];
					$query .= "{$attribute} = ".(($foreign_object->get($foreign_attribute) != NULL) ? "'{$foreign_object->get($foreign_attribute)}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;

			default:
				return NULL;
				break;
		}		
	}
	
	private function query_select($options, $data) {
		$class = $options['lvl1'];
		$lvl2 = $options['lvl2'];
		
		$query = "SELECT * FROM {$class}";

		switch ($lvl2) {
			case "one":
				$query .= " WHERE ";
				$pk = $class::primary_key();
				$first = true;
				foreach ($pk as $attribute) {
					if (!$first)
						$query .= " AND ";
					$query .= "{$attribute} = ".(($data[$attribute] != NULL) ? "'{$data[$attribute]}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;
			
			case "all":
				return $query.";";
				break;

			case "attributes":
				$attributes = $options['attributes'];
				$query .= " WHERE ";
				$first = true;
				foreach ($attributes as $attribute) {
					if (!$first)
						$query .= " AND ";
					$query .= "{$attribute} = ".(($data[$attribute] != NULL) ? "'{$data[$attribute]}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;

			case "foreign":
				$query .= " WHERE ";
				$rel_name = $options['rel_name'];
				$foreign_class = $options['foreign_class'];
				$rk = $class::relational_keys($foreign_class, $rel_name);
				$first = true;
				foreach ($rk as $attribute) {
					if (!$first)
						$query .= " AND ";
					$query .= "{$attribute} = ".(($data[$attribute] != NULL) ? "'{$data[$attribute]}'" : "NULL");
					$first = false;
				}
				return $query.";";
				break;

			default:
				return NULL;
				break;
		}
	}
	
}

?>