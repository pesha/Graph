<?php

class JarnikSkeleton implements Skeleton
{
    
    private $graph;
    
    public function __construct($graph)
    {
        $this->graph = $graph;
    }
    
    public function getSkeleton()
    {
        $roots = $this->graph->getRoots();
        $start = reset($roots);
        
        $data = array('nodes' => array($start), 'edges' => array());
        $result = $this->findSkeleton($data);                
        
        $result['value'] = 0;
        foreach($result['edges'] as $edge){
            $result['value'] += $edge->value;                   
        }
        
        return $result;
    }
    
    public function getTotalValue()
    {
        $data = $this->getSkeleton();
        
        return $data['value'];
    }
    
    private function findSkeleton($data)
    {        
        $lowestEdge = $this->addEdgeToSkeleton($data);
               
        if(!in_array($lowestEdge->getNodeA(), $data['nodes'], true)){
            $data['nodes'][] = $lowestEdge->getNodeA();
        }
        
        if(!in_array($lowestEdge->getNodeB(), $data['nodes'], true)){
            $data['nodes'][] = $lowestEdge->getNodeB();
        }
        
        $data['edges'][] = $lowestEdge;
        
        $nodes = $this->graph->getNodes();
        $found = true;
        foreach($nodes as $node){                       
            if(!in_array($node, $data['nodes'], true)){
                $found = false;
                break;
            }
        }
        
        if(!$found){
            $data = $this->findSkeleton($data);
        }
        
        return $data;
    }
    
    private function addEdgeToSkeleton($data)
    {
        $heap = array();        
        foreach($data['nodes'] as $node){
            $edges = $node->getSurrounding();
            
            foreach($edges as $edge){
                if(!in_array($edge, $heap, true) && !in_array($edge, $data['edges'], true) && $edge->getNodeA()->getName() != $edge->getNodeB()->getName()){
                    $heap[] = $edge;
                }
            }
        }
        
        $lowestEdge = null;
        foreach($heap as $edge){
            if((is_null($lowestEdge) || $edge->value < $lowestEdge->value) && !(in_array($edge->getNodeA(), $data['nodes'], true) && in_array($edge->getNodeB(), $data['nodes'], true))){
                $lowestEdge = $edge;
            }
        }
        
        return $lowestEdge;
    }
}