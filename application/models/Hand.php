<?php

class Application_Model_Hand
{
    protected $_created;
    protected $_content;
    protected $_winner;
    protected $_ante;
    protected $_bb;
    protected $_sb;
    protected $_level;
    protected $_id_fichier;
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
            throw new Exception('Invalid hand property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid hand property');
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
 
    public function setContent($content)
    {
        $this->_content = (string) $content;
        return $this;
    }
 
    public function getContent()
    {
        return $this->_content;
    }
 
    public function setWinner($winner)
    {
        $this->_winner = (string) $winner;
        return $this;
    }
 
    public function getWinner()
    {
        return $this->_winner;
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
 
    public function setAnte($ante)
    {
    	$ante = (int) $ante < 0 ? 0 : (int) $ante;
        $this->_ante = (int) $ante;
        return $this;
    }
 
    public function getAnte()
    {
        return $this->_ante;
    }
 
    public function setBb($bb)
    {
    	$bb = (int) $bb < 0 ? 0 : (int) $bb;
        $this->_bb = (int) $bb;
        return $this;
    }
 
    public function getBb()
    {
        return $this->_bb;
    }
 
    public function setSb($sb)
    {
    	$sb = (int) $sb < 0 ? 0 : (int) $sb;
        $this->_sb = (int) $sb;
        return $this;
    }
 
    public function getSb()
    {
        return $this->_sb;
    }
 
    public function setLevel($level)
    {
        $this->_level = (string) $level;
        return $this;
    }
 
    public function getLevel()
    {
        return $this->_level;
    }
 
    public function setIdFichier($id)
    {
        $this->_id_fichier = (string) $id;
        return $this;
    }
 
    public function getIdFichier()
    {
        return $this->_id_fichier;
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