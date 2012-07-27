<?php

class Application_Model_Player
{
	protected $_created;
	protected $_updated;
	protected $_nb_hands;
	protected $_name;
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
            throw new Exception('Invalid player property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid player property');
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
 
    public function setCreated($ts)
    {
    	if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $ts)) {
    		$ts = date('Y-m-d H:i:s');
    	}
        $this->_created = $ts;
        return $this;
    }
 
    public function getCreated()
    {
        return $this->_created;
    }
 
    public function setUpdated($ts)
    {
    	if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $ts)) {
    		$ts = date('Y-m-d H:i:s');
    	}
        $this->_updated = $ts;
        return $this;
    }
 
    public function getUpdated()
    {
        return $this->_updated;
    }
 
    public function setNbHands($nb_hands)
    {
        $nb_hands = (int) $nb_hands < 0 ? 0 : (int) $nb_hands;
        $this->_nb_hands = $nb_hands;
        return $this;
    }
 
    public function getNbHands()
    {
        return $this->_nb_hands;
    }
 
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }
 
    public function getName()
    {
        return $this->_name;
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