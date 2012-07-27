<?php

class Application_Model_Action
{
    protected $_created;
    protected $_updated;
    protected $_treated;
    protected $_resultat;
    protected $_action_river;
    protected $_action_turn;
    protected $_action_flop;
    protected $_action_preflop;
    protected $_position;
    protected $_name_player;
    protected $_id_hand;
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
            throw new Exception('Invalid action property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid action property');
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
 
    public function setTreated($treated)
    {
        $treated = (int) $treated < 0 ? 0 : (int) $treated;
        $this->_treated = $treated;
        return $this;
    }
 
    public function getTreated()
    {
        return $this->_treated;
    }
 
    public function setResultat($resultat)
    {
        $this->_resultat = (string) $resultat;
        return $this;
    }
 
    public function getResultat()
    {
        return $this->_resultat;
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
 
    public function setActionRiver($action_river)
    {
    	$this->_action_river = (string) $action_river;
        return $this;
    }
 
    public function getActionRiver()
    {
        return $this->_action_river;
    }
 
    public function setActionTurn($action_turn)
    {
    	$this->_action_turn = (string) $action_turn;
        return $this;
    }
 
    public function getActionTurn()
    {
        return $this->_action_turn;
    }
 
    public function setActionFlop($action_flop)
    {
    	$this->_action_flop = (string) $action_flop;
        return $this;
    }
 
    public function getActionFlop()
    {
        return $this->_action_flop;
    }
 
    public function setActionPreflop($action_preflop)
    {
    	$this->_action_preflop = (string) $action_preflop;
        return $this;
    }
 
    public function getActionPreflop()
    {
        return $this->_action_preflop;
    }
 
    public function setPosition($position)
    {
    	$this->_position = (string) $position;
        return $this;
    }
 
    public function getPosition()
    {
        return $this->_position;
    }
 
    public function setNamePlayer($name_player)
    {
    	$this->_name_player = (string) $name_player;
        return $this;
    }
 
    public function getNamePlayer()
    {
        return $this->_name_player;
    }
 
    public function setIdHand($id)
    {
    	$id = (int) $id < 0 ? 0 : (int) $id;
        $this->_id_hand = (int) $id;
        return $this;
    }
 
    public function getIdHand()
    {
        return $this->_id_hand;
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