<?php

class AdjacencyMatrix
{
    /**
     * First dimension is from, second is to
     * @var array
     */
    private $matrix = array();
    
    
    public function getValue($from, $to)
    {
        return isset($this->matrix[$from][$to]) ? $this->matrix[$from][$to] : null;
    }
    
    /**
     * Add data from edge
     * @param Edge $edge
     */
    public function addEdge(Edge $edge)
    {
        $keyA = $edge->getNodeA()->getName();
        $keyB = $edge->getNodeB()->getName();
        
        if(!array_key_exists($keyA, $this->matrix)){
            $this->addNode($keyA);
        }
        
        if(!array_key_exists($keyB, $this->matrix)){
            $this->addNode($keyB);
        }        
        
        switch($edge->getDirection()){
            case Edge::DIR_TWOWAY:                 
                $this->matrix[$keyA][$keyB] += 1;
                $this->matrix[$keyB][$keyA] += 1;
                break;
            case Edge::DIR_ONEWAY:                 
                $this->matrix[$keyA][$keyB] += 1;
                break;
        }
    }
    
    /**
     * Handles matrix dimensions
     * @param string $name
     */
    private function addNode($name)
    {        
        $this->matrix[$name] = array();
        $keys = array_keys($this->matrix);
        foreach($keys as $key){            
            $this->matrix[$name][$key] = 0;
        } 
        
        foreach($this->matrix as $name => $row){
            foreach($keys as $key){
                if(in_array($key, $row)){
                    if(!isset($this->matrix[$name][$key])){
                        $this->matrix[$name][$key] = 0;
                    }
                }
            }
        }                
    }
    
    public function getMatrixAsArray()
    {
        return $this->matrix;
    }    
    
    public function getMatrix()
    {
        return $this;
    }
            
}
