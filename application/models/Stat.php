<?php

class Application_Model_Stat
{
	protected $_id_player;
	protected $_type;
	protected $_valeur;
	protected $_id;
 
	public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid stat property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid stat property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
        	$opt = explode('_', $key);
        	$method = 'set';
        	foreach ($opt as $item) {
        		$method .= ucfirst($item);
        	}
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    public function setIdPlayer($id)
    {
        $id = (int) $id < 0 ? 0 : (int) $id;
        $this->_id_player = $id;
        return $this;
    }
 
    public function getIdPlayer()
    {
        return $this->_id_player;
    }
 
    public function setType($type)
    {
        $this->_type = (string) $type;
        return $this;
    }
 
    public function getType()
    {
        return $this->_type;
    }
 
    public function setValeur($valeur)
    {
        $this->_valeur = (string) $valeur;
        return $this;
    }
 
    public function getValeur()
    {
        return $this->_valeur;
    }
 
    public function setId($id)
    {
    	$id = (int) $id < 0 ? 0 : (int) $id;
        $this->_id = $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}