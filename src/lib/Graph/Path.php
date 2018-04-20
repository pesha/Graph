<?php

class Path
{
    
    private $graph;
    private $nodesNames;
    private $edgesNames;
    
    private $nodes;
    private $edges;
    
    public function __construct($graph)
    {
        $this->graph = $graph;
    }
    
    public function setNodesNames($nodes)
    {
        $this->nodesNames = $nodes;        
    }
    
    public function setEdgesNames($edges)
    {
        $this->edgesNames = $edges;        
    }
    
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }
    
    public function setEdges($edges)
    {
        $this->edges = $edges;
    }
    
    public function pathFound()
    {        
        return (empty($this->nodes) || empty($this->edges)) ? false : true;
    }
    
    public function constructPath()
    {
        $nodes = $this->graph->getNodes();
        
        $this->nodes = array();
        $allEdges = array();                
        foreach($nodes as $node){
            $conns = $node->getConnectionsOut();
            foreach($conns as $edge){
                if(!in_array($edge, $allEdges, true)){
                    $allEdges[] = $edge;
                }
            }
            
            if(array_key_exists($node->getName(),$this->nodesNames)){
                $node->setValue($this->nodesNames[$node->getName()]);
                $this->nodes[] = $node;
            }            
        }
              
        if(empty($this->edges)){
            $this->edges = array();
            foreach($allEdges as $edge){
                if(in_array($edge->name, $this->edgesNames)){
                    $this->edges[] = $edge;
                }
            }                        
        }
    }
    
    public function __toString()
    {        
        $res = "";
        foreach($this->nodes as $node){
            $res .= $node;
        }
        
        foreach($this->edges as $edge){
            $res .= $edge;
        }
        
        return $res;
    }
    
    public function getLength()
    {
        $length = 0;
        foreach($this->nodesNames as $value){
            if($value > $length){
               $length = $value; 
            }
        }
        
        return $length;
    }
    
    
}