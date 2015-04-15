<?php

/**
 * Project:     Framework G - G Light
 * File:        object_standard.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */

class object_standard
{
	function __construct($data = NULL, $components = NULL, $orm = NULL, $auxiliars = NULL)
	{
		if($data != NULL){$this->set_attributes($data,$auxiliars[get_class($this)]);}
		if($components[get_class($this)] != NULL){$this->assign_components($components,$orm,$auxiliars);}
	}
	
	//get attribute
	public function get($attribute){return $this->$attribute;}	
	//set attribute
	public function set($attribute,$value){$this->$attribute = $value;}
	
	//set multiple attributes
	function set_attributes($data, $auxiliars = NULL)
	{
		foreach ($this->metadata() as $key => $attribute)
		{
			if(!is_empty($data->$key)){$this->set($key,$data->$key);}
		}
		
		if($auxiliars != NULL)
		{
			foreach ($auxiliars as $attribute_aux)
			{if(!is_empty($data->$attribute_aux)){$this->auxiliars[$attribute_aux]=$data->$attribute_aux;}}
		}
	}
	
	//assign foreign relations to objetcs
	function assign_components($components,$orm,$auxiliars = NULL)
	{
		foreach ($components[get_class($this)] as $class_ref => $rels)
		{
			$j=0;
			while($j < count($rels)) //one component can have one or more relations
			{
				$name_rel = $rels[$j]; $pos=0;
				$data = $orm->get_data($class_ref,$pos);
				while($data != NULL)
				{
					$rel_one='';$rel_two='';$f=0;$ft=0;unset($rel_aux);
								
					if (get_class($this) == $class_ref) //when is a foreign to the own table
					{
						$rel = $this->relational_keys($class_ref, $name_rel);
						if(!is_empty($rel))
						{
							$attribute_info=$this->metadata();
							while($ft < count($rel)){$rel_one=$rel_one.$data->$rel[$ft];
							$rel_aux[]=$attribute_info[$rel[$ft]]['foreign_attribute'];$ft++;}$ft=0;
							while($ft < count($rel_aux)){$rel_two=$rel_two.$this->get($rel_aux[$ft]);$ft++;}
							
							if($rel_one == $rel_two)
							{$this->components[$class_ref][$name_rel][]= new $class_ref($data,$components,$orm,$auxiliars);}
						}
					}
					else //when is a foreign to other table
					{
						$class_ref_aux = new $class_ref();
						$rel_this = $this->relational_keys($class_ref, $name_rel);
						$rel = $class_ref_aux->relational_keys(get_class($this), $name_rel);
				
						if(is_empty($rel) && is_empty($rel_this))
						{
							if(substr($name_rel,0,1) == '-') // when is a indirect relation
							{$this->components[$class_ref][$name_rel][]= new $class_ref($data,$components,$orm,$auxiliars);}
						}
						else
						{
							if(is_empty($rel))
							{
								$attribute_info=$this->metadata();
								while($f < count($rel_this)){$rel_one=$rel_one.$this->get($rel_this[$f]);
								$rel_aux[]=$attribute_info[$rel_this[$f]]['foreign_attribute'];$f++;}$f=0;
								while($f < count($rel_aux)){$rel_two=$rel_two.$data->$rel_aux[$f];$f++;}
							}
							else
							{
								$attribute_info=$class_ref_aux->metadata();
								while($ft < count($rel)){$rel_one=$rel_one.$data->$rel[$ft];
								$rel_aux[]=$attribute_info[$rel[$ft]]['foreign_attribute'];$ft++;}$ft=0;
								while($ft < count($rel_aux)){$rel_two=$rel_two.$this->get($rel_aux[$ft]);$ft++;}
							}
							if($rel_one == $rel_two)
							{$this->components[$class_ref][$name_rel][]= new $class_ref($data,$components,$orm,$auxiliars);}
						}	
					}
					
					$pos++;
					$data = $orm->get_data($class_ref,$pos);
				}
				$j++;
			}
		}
	}	
}

?>