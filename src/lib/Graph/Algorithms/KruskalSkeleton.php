<?php

class KruskalSkeleton implements Skeleton
{

    private $graph;
    private $edges;
    
    public function __construct($graph)
    {
        $this->graph = $graph;
    }
    
    public function getSkeleton()
    {
        $nodes = $this->graph->getNodes();
        $this->edges = array();
        foreach($nodes as $node){
            $surrounding = $node->getSurrounding();
            foreach($surrounding as $edge){
                if(!in_array($edge, $this->edges, true)){
                    $this->edges[] = $edge;
                }
            }
        }
                        
        $data = array('edges' => array());
        
        $result = $this->addEdgeToSkeleton($data);
        $result['value'] = 0;
        foreach($result['edges'] as $edge){
            $result['value'] += $edge->value;                   
        }
        
        return $result;
    }
    
    private function addEdgeToSkeleton($data)
    {
        $lowest = null;
        foreach($this->edges as $edge){
            if($edge->getNodeA()->getName() == $edge->getNodeB()->getName() || in_array($edge, $data['edges'], true)){
                continue;
            }
            if((is_null($lowest) || $edge->value < $lowest->value) && !$this->checkCycle($data['edges'], $edge)){
                $lowest = $edge;
            }
        }
        
        if(!is_null($lowest)){          
            $data['edges'][] = $lowest;
            $data = $this->addEdgeToSkeleton($data);
        }
        
        return $data;
    }
    
    private function checkCycle($usedEdges, $actualEdge)
    {
        $nodes = array();
        $edges = $usedEdges;
        $edges[] = $actualEdge;
        foreach($edges as $edge){
            if(!in_array($edge->getNodeA(), $nodes, true)){
                $nodes[] = $edge->getNodeA();
            }
            if(!in_array($edge->getNodeB(), $nodes, true)){
                $nodes[] = $edge->getNodeB();
            }
        }
        
        $constructer = new Constructer();
        foreach($nodes as $node){                 
            $constructer->addNode($node->_toArray());
        }
        foreach($edges as $edge){            
            $constructer->addEdge($edge->_toArray());
        }
        
        $graph = $constructer->getGraph();
        $nodes = $graph->getNodes();
        $cycle = false;

        foreach($nodes as $node){                   
            if($graph->existWay($node, $node, true)){
                $cycle = true;
                break;
            }
            $graph->clrTmp();
        }
               
        return $cycle;
    }
            
    public function getTotalValue()
    {
        $result = $this->getSkeleton();
        
        return $result['value'];
    }
    
}
