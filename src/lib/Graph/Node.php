<?php

/**
 * Node
 *
 * @author Martin Péchal
 */
class Node
{

    const DIR_IN = 'in';
    const DIR_OUT = 'out';
    const DIR_BOTH = 'both';

    private $name;
    private $value;
    private $connectionsOut = array();
    private $connectionsIn = array();

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getConnectionsIn()
    {
        return $this->connectionsIn;
    }

    public function getConnectionsOut()
    {
        return $this->connectionsOut;
    }

    public function connectTo(Node $node, $direction = Edge::DIR_TWOWAY, $edgeValue = null, $edgeName = null)
    {
        $edge = new Edge($this, $node, $direction, $edgeValue, $edgeName);

        $node->connectIn($edge);
        $this->connectionsOut[] = $edge;
    }

    private function connectIn(Edge $edge)
    {
        $this->connectionsIn[] = $edge;
    }
    
    public function getSurrounding($direction = self::DIR_BOTH)
    {
        $edges = array();
        
        if($direction == self::DIR_IN || $direction == self::DIR_BOTH){
            foreach($this->connectionsIn as $edge){                    
                if(!in_array($edge,$edges, true)){
                    $edges[] = $edge;
                }
            }
        }
        
        if($direction == self::DIR_OUT || $direction == self::DIR_BOTH){
            foreach($this->connectionsOut as $edge){                    
                if(!in_array($edge,$edges, true)){
                    $edges[] = $edge;
                }
            }
        }        
        
        return $edges;
    }

    public function getNodeDegree($direction = self::DIR_BOTH)
    {
        $edges = $this->getSurrounding($direction);        
        return count($edges);
    }
    
    public function getNeighbors($direction = self::DIR_BOTH)
    {
        $nodes = array();

        if($direction == self::DIR_IN || $direction == self::DIR_BOTH){
            foreach($this->connectionsIn as $edge){                    
                if(!in_array($edge->getNodeA(),$nodes, true)){
                    $nodes[] = $edge->getNodeA();
                }
            }
        }

        if($direction == self::DIR_OUT || $direction == self::DIR_BOTH){
            foreach($this->connectionsOut as $edge){                    
                if(!in_array($edge->getNodeB(),$nodes, true)){
                    $nodes[] = $edge->getNodeB();
                }
            }
        }
        
        return $nodes;
    }
    
    public function _toString()
    {
        $string = 'u '.$this->name;
        
        if(!empty($this->value) || (empty($this->value) && $this->value == 0)){
            $string .= ' ' . $this->value;
        }
        
        return $string;
    }
    
    public function __toString()
    {
        return $this->_toString().PHP_EOL;
    }
    
    public function _toArray()
    {
        $data = array('name' => $this->name);        
        $data['value'] = !empty($this->value) ? $this->value : null;
        
        return $data;
    }
    
    public function removeEdge(Edge $edge)
    {
        foreach($this->connectionsIn as $key => $item){
            if($edge === $item){
                unset($this->connectionsIn[$key]);
            }
        }
        
        foreach($this->connectionsOut as $key => $item){
            if($edge === $item){
                unset($this->connectionsOut[$key]);
            }
        }        
    }
    
    public function getEdges()
    {
        $edges = array();
        foreach($this->connectionsIn as $edge){
            $edges[] = $edge;            
        }
        
        foreach($this->connectionsOut as $edge){            
            if(!in_array($edge, $edges, true)){
                $edges[] = $edge;
            }
        }
        
        return $edges;
    }
    
    public function __get($name)
    {
        switch($name){
            case 'name': return $this->name; break;
            case 'value': return $this->value; break;
            default: 
                throw new InvalidArgumentException("Member $name doesnt exist");
            break;
        }
    }

}
