<?php

class DijkstrOptimalPath
{
    
    private $graph;
    private $state;
    private $steps;
    
    public function __construct($graph)
    {
        $this->graph = $graph;
    }
    
    public function findOptimalPath($start, $end)
    {
        $this->initState($start); 
        
        $found = false;
        foreach($this->graph->getNodes() as $node){
            if($node->getName() == $end){
                $found = true;
            }
        }
        
        if(!$found) return false;
        
        $this->steps[] = array($start,'-','0');
        $startNode = $this->getNodeByName($start);
        $this->iterate($startNode);
        
        foreach($this->state as $key => $data){
            if($key == $end && $data['done'] == false){
                return false;
            }
        }

        $nodes = $this->findPathNodes($end);
        $edges = $this->findPathEdges($end);
                
        $path = new Path($this->graph);
        $path->setNodesNames($nodes);
        $path->setEdges($edges);
        $path->constructPath();
        
        return $path;
    }
    
    private function iterate(Node $node)
    {       
        $edges = $node->getConnectionsOut();
              
        foreach($edges as $edge){
            $nodeB = $edge->getNodeB();
            $distance = $edge->getValue();
            
            foreach($this->state as $key => $data){
                if($key == $nodeB->getName() && !$data['done']){
                    $this->state[$key]['node'] = $node->getName();
                    $this->state[$key]['distance'] = $distance + $this->state[$node->getName()]['distance'];
                    $this->state[$key]['edge'] = $edge;
                    $this->state[$node->getName()]['done'] = true;
                    
                    $this->steps[] = array($nodeB->getName(),$node->getName(),$this->state[$key]['distance']);
                    break;
                }
            }
        }
        
        $name = $this->selectItem();
        if($name != ""){
            $this->state[$name]['done'] = true;
            $this->iterate($this->getNodeByName($name));
        }
    }
    
    private function selectItem()
    {
        $smallest = array('name' => '', 'min' => PHP_INT_MAX);
        foreach($this->state as $key => $data){
            if($data['done'] == false && $data['node'] != '-'){
                if($data['distance'] < $smallest['min']){
                    $smallest['name'] = $key;
                    $smallest['min'] = $data['distance'];
                }
            }
        }
        
        return $smallest['name'];
    }
    
    private function getNodeByName($name)
    {
        $nodes = $this->graph->getNodes();
        
        foreach($nodes as $node){
            if($node->getName() == $name){
                return $node;
            }
        }
    }
    
    private function findPathNodes($end)
    {
        $nodes = array();
        
        foreach($this->state as $name => $data){
            if($name == $end){                
                $nodes[$end] = $data['distance'];
                $nodesInner = $this->findPathNodes($data['node']);
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
            if($name == $end){          
                if(!empty($data['edge'])){
                    $nodes[] = $data['edge'];
                    $nodesInner = $this->findPathEdges($data['node']);
                    foreach($nodesInner as $nInner){
                        $nodes[] = $nInner;
                    }                
                }
            }
        }
        
        return $nodes;    
    }    

       
    private function initState($startNode)
    {
        $nodes = $this->graph->getNodes();
        
        $this->state = array();
        $this->state[$startNode] = array('node' => '-', 'distance' => 0, 'done' => false);
        foreach($nodes as $node){
            if($node->getName() != $startNode){
                $this->state[$node->getName()] = array('node' => '-', 'distance' => PHP_INT_MAX, 'done' => false);
            }
        }
    }
    
    public function checkConditions()
    {
        $state = true;
        foreach($this->graph->getNodes() as $node){
            $conns = $node->getConnectionsOut();
            foreach($conns as $edge){
                if($edge->getValue() < 0){
                    return false;
                }
            }
        }
        
        return $state;
    }
    
    public function getSteps()
    {
        return $this->steps;
    }

}

