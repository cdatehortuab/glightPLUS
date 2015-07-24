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

abstract class object_standard implements JsonSerializable
{

	protected static $orm;
	protected static $last_select = array();

	var $auxiliars = array();

	var $components = array();

	function __construct($data = NULL, $components = NULL, $orm = NULL, $auxiliars = NULL)
	{
		if($data != NULL){$this->set_attributes($data,$auxiliars[get_class($this)]);}
		if(isset($components[get_class($this)]) && $components[get_class($this)] != NULL){$this->assign_components($components,$orm,$auxiliars);}
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
			if (isset($data->$key) && !is_empty($data->$key)) {
				$this->set($key,$data->$key);
			} else {
				$this->set($key, NULL);
			}
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

	public function insert($option = "normal") {
		$orm = self::$orm;
		$orm->connect();
		$orm->insert_data($option, $this);
		$orm->close();
	}

	public function update($new, $option = "normal") {
		$orm = self::$orm;
		$class = get_class($this);

		foreach ($class::primary_key() as $value) {
			$new->auxiliars[$value] = $this->$value;
		}

		$orm->connect();
		$orm->update_data($option, $new);
		$orm->close();
	}

	public function delete($option = "normal") {
		$orm = self::$orm;
		$orm->connect();
		$orm->delete_data($option, $this);
		$orm->close();
	}

	public function jsonSerialize() {
		$return = array();
		$class = get_class($this);
		foreach ($class::metadata() as $attribute => $value) {
			$return[$attribute] = $this->$attribute;
		}
		$return['components'] = $this->components;
		$return['auxiliars'] = $this->auxiliars;

		return $return;
	}

	static function initialize_orm() {
		self::$orm = new orm();
	}

	private static function select_all($components = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		if (isset($last_select[$called_class]) && $last_select[$called_class] == "all")
			return;
		
		$options[$called_class]['lvl2'] = "all";
		$orm->read_data(array($called_class), $options);
		$last_select[$called_class] = "all";

		if ($components != NULL && isset($components[$called_class])) {
			foreach ($components[$called_class] as $class=>$relations) {
				foreach ($relations as $rel_name) {
					$rk1 = $called_class::relational_keys($class, $rel_name);
					$rk2 = $class::relational_keys($called_class, $rel_name);
					if ($rk1 != NULL || $rk2 != NULL) {
						$class::select_all();
					}
				}
			}
		}
	}

	private static function select_by_foreign($object, $rel_name, $components = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();
		$metadata = $called_class::metadata();
		$foreign_class = get_class($object);


		$options[$called_class]['lvl2'] = "foreign";
		$options[$called_class]['foreign_class'] = $foreign_class;
		$options[$called_class]['rel_name'] = $rel_name;
		$attributes = $called_class::relational_keys($foreign_class, $rel_name);

		foreach ($attributes as $value) {
			$cod[$called_class][$value] = $object->get($metadata[$value]['foreign_attribute']);
		}

		$orm->read_data(array($called_class), $options, $cod);

		if ($components != NULL && isset($components[$called_class])) {
			foreach ($components[$called_class] as $class=>$relations) {
				$class::select_all($components);
			}
		}

	}

	private static function select_one($object, $components = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$options[$called_class]['lvl2'] = "one";
		$pk = $called_class::primary_key();
		foreach ($pk as $value) {
			$cod[$called_class][$value] = $object->$value;
		}

		$orm->read_data(array($called_class), $options, $cod);
		$object = $orm->get_objects($called_class);
		
		if (is_empty($object)) return;

		$object = $object[0];

		if ($components != NULL && isset($components[$called_class])) {
			foreach ($components[$called_class] as $class=>$relations) {
				foreach ($relations as $rel_name) {
					$rk1 = $called_class::relational_keys($class, $rel_name);
					$rk2 = $class::relational_keys($called_class, $rel_name);
					if ($rk1 != NULL) {
						$fa = array();
						$af = array();
						$metadata = $called_class::metadata();
						foreach ($rk1 as $attribute) {
							$fa[$attribute] = $metadata[$attribute]['foreign_attribute'];
							$af[$fa[$attribute]] = $attribute;
						}
						$pkf = $class::primary_key();
						if (is_empty(array_diff($pkf, $fa))) {
							foreach ($pkf as $value) {
								@$obj->$value = $object->get($af[$value]);
							}
							$class::select_one($obj, $components);
						} else {
							// TODO : Lo que pasa cuando la clave foránea de una no es la primaria de la otra
						}
					} else if ($rk2 != NULL) {
						$fa = array();
						$metadata = $class::metadata();
						foreach ($rk2 as $attribute) {
							$fa[] = $metadata[$attribute]['foreign_attribute'];
						}
						if (is_empty(array_diff($pk, $fa))) {
							$class::select_by_foreign($object, $rel_name, $components);
						} else {
							// TODO : Lo que pasa cuando la clave foránea de una no es la primaria de la otra
						}
					}
				}
			}
		}
	}

	public static function select_by_attributes($object, $attributes, $components = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$options[$called_class]['lvl2'] = "attributes";
		$options[$called_class]['attributes'] = $attributes;

		foreach ($attributes as $attribute) {
			$cod[$called_class][$attribute] = $object->$attribute;
		}

		$orm->read_data(array($called_class), $options, $cod);

		if ($components != NULL && isset($components[$called_class])) {
			foreach ($components[$called_class] as $class=>$relations) {
				$class::select_all($components);
			}
		}
	}

	public static function get_all($components = NULL, $auxiliars = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();
		$orm->connect();

		self::select_all($components);

		$return = $orm->get_objects($called_class, $components, $auxiliars);
		$orm->close();

		return $return;
	}

	public static function get_one($object, $components = NULL, $auxiliars = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$orm->connect();

		self::select_one($object, $components);

		$return = $orm->get_objects($called_class, $components, $auxiliars);
		$orm->close();

		return $return;
	}

	public static function get_by_attributes($object, $attributes, $components = NULL, $auxiliars = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$orm->connect();

		self::select_by_attributes($object, $attributes, $components);

		$return = $orm->get_objects($called_class, $components, $auxiliars);
		$orm->close();

		return $return;
	}

	public static function get_by_custom_options($options, $cod = NULL, $components = NULL, $auxiliars = NULL) {
		$orm = self::$orm;

		$classes = array();

		foreach ($options as $class => $option) {
			$classes[] = $class;
			unset($options[$class]);
			$options[$class]['lvl2'] = $option;
		}

		$orm->connect();
		$orm->read_data($classes, $options, $cod);
		$return = $orm->get_objects(get_called_class(), $components, $auxiliars);
		$orm->close();

		return $return;
	}

	public static function get_by_foreign($object, $rel_name, $components = NULL, $auxiliars = NULL) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$orm->connect();

		self::select_by_foreign($object, $rel_name, $components);

		$return = $orm->get_objects($called_class, $components, $auxiliars);
		$orm->close();

		return $return;
	}

	public static function insert_multiples($objects, $option = "multiples") {
		$orm = self::$orm;
		$called_class = get_called_class();

		$orm->connect();
		$orm->insert_data($option, $objects, $called_class);
		$orm->close();
	}

	public static function delete_all() {
		$orm = self::$orm;
		$called_class = get_called_class();

		$orm->connect();
		$orm->delete_data("all", new $called_class());
		$orm->close();
	}

	public static function delete_by_foreign($object, $rel_name) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$obj = new $called_class();
		$obj->auxiliars['foreign_object'] = $object;
		$obj->auxiliars['rel_name'] = $rel_name;

		$orm->connect();
		$orm->delete_data("foreign", $obj);
		$orm->close();
	}

	public static function delete_by_attributes($object, $attributes) {
		$orm = self::$orm;
		$called_class = get_called_class();

		$object = new $called_class($object);
		$object->auxiliars['attributes'] = $attributes;

		$orm->connect();
		$orm->delete_data("attributes", $object);
		$orm->close();
	}
}

object_standard::initialize_orm();

?>
