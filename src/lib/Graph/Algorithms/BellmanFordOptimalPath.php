<?php

class BellmanFordOptimalPath
{

    private $graph;
    private $state;
    private $steps;
    private $ok = true;
    
    private $buffer = array();
    private $buffer2 = array();    
    
    public function __construct($graph)
    {
        $this->graph = $graph;
    }
    
    public function findOptimalPath($start, $end)
    {                
        $this->initState($start);
        $nodes = $this->graph->getNodes();
        $totalNodes = count($nodes);
        
        $allEdges = array();
        foreach($nodes as $node){
            $edges = $node->getConnectionsOut();                
            foreach($edges as $edge){
                if(!in_array($edge, $allEdges, true)){
                    $allEdges[] = $edge;
                }
            }
        }
  
        $step = 1;
        while($step < $totalNodes){
            $this->steps[$step] = array();
            
            foreach($allEdges as $edge){            
                $updated = array();
                if($this->state[$edge->getNodeB()->name]['length'] > ($this->state[$edge->getNodeA()->name]['length'] + $edge->getValue())){
                   $this->state[$edge->getNodeB()->name]['length'] = $this->state[$edge->getNodeA()->name]['length'] + $edge->getValue();
                   $this->state[$edge->getNodeB()->name]['lastNode'] = $edge->getNodeA()->name;
                   $this->state[$edge->getNodeB()->name]['edges'] = $this->state[$edge->getNodeA()->name]['edges'] + 1;                                     
                   $name = $edge->getName();
                   if(!empty($name)){                       
                       $this->state[$edge->getNodeB()->name]['edge'] = $edge->name;
                   }
                   
                   if(!empty($this->steps[$step][$edge->getNodeA()->name]['updated'])){
                        $updated = $this->steps[$step][$edge->getNodeA()->name]['updated'];
                   }
                   $updated[] = $edge->getNodeB()->name;
                   $this->steps[$step][$edge->getNodeA()->name] = $this->state[$edge->getNodeB()->name];
                   $this->steps[$step][$edge->getNodeA()->name]['updated'] = $updated;                   
                }
            }
                        
            $step++;
        }
        
        // kontrola cyklu zaporne delky
        foreach($allEdges as $edge){            
            if($this->state[$edge->getNodeB()->name]['length'] > ($this->state[$edge->getNodeA()->name]['length'] + $edge->getValue())){
                $this->ok = false;
            }
        }        
        

        $pathNodes = $this->findPathNodes($end);
        $pathEdges = $this->findPathEdges($end);
        
        
        $path = new Path($this->graph);
        $path->setNodesNames($pathNodes);
        $path->setEdgesNames($pathEdges);
                        
        return $path;
    }
    
    public function isOk()
    {
        return $this->ok;
    }
    
    private function findPathNodes($end)
    {
        $nodes = array();
        
        foreach($this->state as $name => $data){
            if($name == $end && !in_array($name, $this->buffer)){   
                $this->buffer[] = $name;
                $nodes[$end] = $data['length'];
                $nodesInner = $this->findPathNodes($data['lastNode']);
                foreach($nodesInner as $key => $nInner){
                    $nodes[$key] = $nInner;
                }
            }
        }
        
        return $nodes;
    }
    
    private function findPathEdges($end)
    {
        $nodes = array();
        
        foreach($this->state as $name => $data){
            if($name == $end && !in_array($name, $this->buffer2)){          
                if(!empty($data['edge'])){
                    $this->buffer2[] = $name;
                    $nodes[] = $data['edge'];
                    $nodesInner = $this->findPathEdges($data['lastNode']);
                    foreach($nodesInner as $nInner){
                        $nodes[] = $nInner;
                    }                
                }
            }
        }
        
        return $nodes;    
    }
    
    private function initState($start)
    {
        $nodes = $this->graph->getNodes();
        
        $this->state = array();        
        foreach($nodes as $item){
            if($item->getName() != $start){
                $this->state[$item->getName()] = array('edges' => 0, 'lastNode' => '', 'length' => PHP_INT_MAX);
            } else {
                $this->state[$item->getName()] = array('edges' => 0, 'lastNode' => '', 'length' => 0);
            }
        }
    }
    
    public function getSteps()
    {
        return $this->steps;
    }
    
}