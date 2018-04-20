<?php

/**
 * Edge
 *
 * @author Martin Péchal
 */
class Edge
{

    const DIR_TWOWAY = 'tw';
    const DIR_ONEWAY = 'ow';

    private $nodeA;
    private $nodeB;
    private $value;
    private $name;
    private $direction;

    public function __construct(Node $nodeA, Node $nodeB, $direction = self::DIR_TWOWAY, $value = null, $name = null)
    {
        $this->nodeA = $nodeA;
        $this->nodeB = $nodeB;

        $this->value = $value;
        $this->name = $name;
        $this->direction = $direction;
    }

    public function isLoop()
    {
        return ($this->nodeA === $this->nodeB) ? true : false;
    }

    public function getNodeA()
    {
        return $this->nodeA;
    }

    public function getNodeB()
    {
        return $this->nodeB;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function _toString()
    {
        $value = !empty($this->value) ? $this->value : '';
        $name = !empty($this->name) ? ':' . $this->name : '';

        $dir = ($this->direction == self::DIR_TWOWAY) ? '-' : '>';

        $string = 'h ' . $this->nodeA->getName() . ' ' . $dir . ' ' . $this->nodeB->getName() . ' ' . $value . ' ' . $name;

        return $string;
    }

    public function __toString()
    {
        return $this->_toString() . PHP_EOL;
    }
    
    public function _toArray()
    {
       $data = array(
           'nodeA' => $this->getNodeA()->getName(),
           'direction' => ($this->direction == self::DIR_TWOWAY) ? '-' : '>',
           'nodeB' => $this->getNodeB()->getName(),           
       );
       
       return $data;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'value': return $this->value;
                break;
            case 'name': return $this->name;
                break;                
            case 'direction': return $this->direction;
                break;
            default: throw new InvalidArgumentException("Member with $name doesnt exist");
                break;
        }
    }

}
