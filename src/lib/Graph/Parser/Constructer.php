<?php

/**
 * Description of Contructer
 *
 * @author Martin
 */
class Constructer
{
    
    private $graph;
    private $nodes = array();
    
    public function __construct()
    {
        $this->graph = new Graph();        
    }

    
    public function addNode($data)
    {
       $node = new Node($data['name']);
       if(isset($data['value'])) $node->setValue($data['value']);
       
       $this->nodes[$node->getName()] = $node;
       
       $roots = $this->graph->getRoots();
       if(empty($roots)){
           $this->graph->addRoot($node);
       }              
    }
    
    public function addEdge($data)
    {        
        $direction = ($data['direction'] == '>') ? Edge::DIR_ONEWAY : Edge::DIR_TWOWAY;
        $value = !empty($data['value']) ? $data['value'] : null;
        $name = !empty($data['name']) ? $data['name'] : null;
        
        $this->nodes[$data['nodeA']]->connectTo($this->nodes[$data['nodeB']], $direction, $value, $name);                
    }
    
    /**
     * 
     * @return Graph
     */
    public function getGraph($checkOut = false)
    {
        //todo neresi vsechny situace sirotku
        foreach($this->nodes as $node)
        {            
            if(count($node->getConnectionsIn()) == 0 || count($node->getConnectionsOut()) == 0){
                $this->graph->addRoot($node);
            }
            if($checkOut == true){
                if(count($node->getConnectionsIn()) == 0){
                    if(!in_array($node,$this->graph->getRoots())){
                        $this->graph->addRoot($node);
                    }
                }   
            }
        }
            
        return $this->graph;
    }
    
    public function getNodes()
    {                
        return $this->nodes;
    }
    
}