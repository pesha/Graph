<?php

/**
 * Node
 *
 * @author Martin Péchal
 */
class Graph
{

    private $roots = array();
    private $tmp = array();

    public function addRoot(Node $node)
    {
        $this->roots[$node->getName()] = $node;
    }

    public function getRoots()
    {
        return $this->roots;
    }
    
    public function removeRoot(Node $node)
    {
        foreach($this->roots as $key => $root){
            if($root === $node){
                unset($this->roots[$key]);
            }
        }
    }

    public function isOriented()
    {
        foreach ($this->roots as $node) {
            $edges = $node->getEdges();
            foreach ($edges as $edge) {
                return ($edge->getDirection() == Edge::DIR_ONEWAY) ? true : false;
            }
        }
    }

    public function getAdjacencyMatrix()
    {
        $matrix = new AdjacencyMatrix();

        $start = reset($this->roots);
        $uniqueEdges = $this->findEdges(array(), $start);

        // because of components
        if (count($this->roots) > 1) {
            foreach ($this->roots as $node) {
                if ($node != $start)
                    $uniqueEdges = $this->findEdges($uniqueEdges, $node);
            }
        }
                
        foreach ($uniqueEdges as $edge){
            $matrix->addEdge($edge);
        }
        
        return $matrix;
    }

    private function findEdges($uniqueEdges, Node $node)
    {
        $edges = $node->getConnectionsOut();
        $added = false;
        if (!empty($edges)) {
            foreach ($edges as $edge) {
                $found = false;
                foreach ($uniqueEdges as $uniqueEdge) {
                    if ($uniqueEdge === $edge) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $uniqueEdges[] = $edge;
                    $added = true;
                }
            }
            if ($added) {
                foreach ($edges as $edge) {
                    $uniqueEdges = $this->findEdges($uniqueEdges, $edge->getNodeB());
                }
            }
        }
        
        return $uniqueEdges;
    }

    public function isComplete()
    {
        $state = $this->isSimple() ? true : 'none';

        $nodes = $this->getNodes();
        $allNames = array();
        foreach ($nodes as $node) {
            $allNames[] = $node->getName();
        }
        $names = array();
        if ($this->isOriented()) {
            foreach ($nodes as $node) {
                $present = array();
                $nextIn = $node->getNeighbors(Node::DIR_IN);
                $nextOut = $node->getNeighbors(Node::DIR_OUT);

                foreach ($nextIn as $it) {                    
                    if (!isset($present[$node->getName()][$it->getName()])) {
                        $present[$node->getName()][$it->getName()] = 0;
                    }
                    $present[$node->getName()][$it->getName()] += 1;
                }

                foreach ($nextOut as $it) {
                    if (!isset($present[$node->getName()][$it->getName()])) {
                        $present[$node->getName()][$it->getName()] = 0;
                    }
                    $present[$node->getName()][$it->getName()] += 1;
                }

                foreach ($present as $node => $item) {
                    foreach ($item as $key => $it) {
                        if ($it == 2)
                            $names[$node][] = $key;
                    }
                }
            }
        } else {
            foreach ($nodes as $node) {
                $next = $node->getNeighbors();
                foreach ($next as $it) {
                    $names[$node->getName()][] = $it->getName();
                }
            }
        }

        foreach ($names as $node => $neighbors) {
            $allNeighbors = $neighbors;
            $allNeighbors[] = $node;
            $res = array_diff($allNames, $allNeighbors);
            if (!empty($res)) {
                $state = false;
                break;
            }
        }

        return $state;
    }

    public function isRegular()
    {
        $data = $this->regularCheck();
        return $data['isRegular'];
    }

    public function getRegularDegree()
    {
        $data = $this->regularCheck();
        return !empty($data['degree']) ? $data['degree'] : false;
    }

    private function regularCheck()
    {
        $isRegular = $this->isNotMultigraph() ? true : 'none';
        if ($isRegular) {
            $nodes = $this->getNodes();

            $lastDegree = null;
            foreach ($nodes as $node) {
                $degree = $node->getNodeDegree();
                if ($degree != $lastDegree && $lastDegree != null) {
                    $isRegular = false;
                    break;
                }
                $lastDegree = $degree;
            }
        }

        $result = array('isRegular' => $isRegular);
        if ($isRegular)
            $result['degree'] = $lastDegree;

        return $result;
    }

    public function isSymetric()
    {
        $nodes = $this->getNodes();
        $symetric = true;
        
        if(!$this->isOriented())
            return true;
        
        $out = array();
        foreach ($nodes as $node) {                                 
            foreach ($node->getConnectionsOut() as $edge) {
                if(!isset($out[$node->getName().'-'.$edge->getNodeB()->getName()])){
                    $out[$node->getName().'-'.$edge->getNodeB()->getName()] = array('a' => $node->getName(), 'b' => $edge->getNodeB()->getName(), 'count' => 1);
                } else {
                    $out[$node->getName().'-'.$edge->getNodeB()->getName()]['count']++;    
                }
            }                       
        }

        $copy = $out;
        foreach($out as $item){
            $found = false;
            foreach($copy as $sec){
                if($item['a'] == $sec['b'] && $item['b'] == $sec['a'] && $item['count'] == $sec['count']){
                    $found = true;
                }
            }
            if($found == false){
                $symetric = false;
                break;
            }
        }

        return $symetric;
    }

    public function isNotMultigraph()
    {
        $matrix = $this->getAdjacencyMatrix()->getMatrixAsArray();
        
        $max = $this->isOriented() ? 1 : 2;

        foreach ($matrix as $a => $row) {
            foreach ($row as $b => $column) {
                if (($column > 1 && $a != $b) || ($column > $max && $a == $b)) {
                    // only one wrong value is sufficient
                    return false;
                }
            }
        }
        
        return true;
    }

    public function isSimple()
    {
        $simple = $this->isNotMultigraph();
        $matrix = $this->getAdjacencyMatrix()->getMatrixAsArray();

        foreach ($matrix as $a => $row) {
            foreach ($row as $b => $column) {
                if ($a == $b && $column != 0) {
                    $simple = false;
                }
            }
        }

        return $simple;
    }

    public function getLoops()
    {
        $loops = array();
        $nodes = $this->getNodes();
        foreach ($nodes as $node) {
            $out = $node->getConnectionsOut();
            foreach ($out as $edge) {
                if ($node == $edge->getNodeB()) {
                    $loops[] = $edge;
                }
            }
        }

        return $loops;
    }

    public function hasLoops()
    {
        $loops = $this->getLoops();
        return !empty($loops) ? true : false;
    }

    public function getNodes()
    {
        $start = reset($this->roots);
        $uniqueNodes = $this->findNodes(array(), $start);

        // because of components
        if (count($this->roots) > 1) {
            foreach ($this->roots as $node) {
                if ($node != $start)
                    $uniqueNodes = $this->findNodes($uniqueNodes, $node);
            }
        }

        return $uniqueNodes;
    }
    
    

    private function findNodes($uniqueNodes, Node $node)
    {
        $edges = $node->getConnectionsOut();
        $found = false;
        foreach ($uniqueNodes as $uniqueNode) {
            if ($uniqueNode == $node) {
                $found = true;
                break;
            }
        }

        if ($found == false)
            $uniqueNodes[] = $node;

        foreach ($edges as $edge) {
            if (!in_array($edge->getNodeB(), $uniqueNodes,true)) {
                $uniqueNodes = $this->findNodes($uniqueNodes, $edge->getNodeB());
            }
        }

        return $uniqueNodes;
    }

    public function getSubgraph($fromNodes)
    {
        $nodes = $this->getNodes();
        
        foreach ($nodes as $key => $node) {            
            if (!in_array($node->getName(), $fromNodes)) {
                unset($nodes[$key]);
            } 
                foreach ($node->getConnectionsOut() as $edge) {
                    if (!in_array($edge->getNodeB()->getName(), $fromNodes)) {
                        $node->removeEdge($edge);
                    }
                }

                foreach ($node->getConnectionsIn() as $edge) {
                    if (!in_array($edge->getNodeA()->getName(), $fromNodes)) {
                        $node->removeEdge($edge);
                    }
                }
            
        }

        return $nodes;
    }
    
    public function getComponents()
    {
        $components = array();
        
        foreach($this->roots as $node){            
            if($node->getConnectionsIn() == 0 && $node->getConnectionsOut() == 0){                
                $components[] = array($node);                
            } else {
                $uniqueNodes = array();
                $found = false;
                foreach($components as $component){
                    if(in_array($node, $component)){
                        $found = true;
                    }
                }
                
                if(!$found){
                    $components[] = $this->findNodesExtended($uniqueNodes, $node);
                }
            }
        }
        
        return $components;        
    }
    
    public function getComponent($nodeName, $withEdges = false)
    {
        $components = $this->getComponents();
        
        $result = array();
        foreach($components as $component){
            foreach($component as $node){
                if($node->getName() == $nodeName){
                    $result = $component;
                    break;
                    break;
                }
            }
        }
        
        $edges = array();
        if($withEdges){
            foreach($result as $node){
                $foundEdges = $node->getEdges();
                foreach($foundEdges as $edge){
                    if(!in_array($edge, $edges, true)){
                        $edges[] = $edge;
                    }
                }
            }
            
            $result = array('nodes' => $result, 'edges' => $edges);
        }
        
        return $result;
    }
    
    public function getComponentsCount()
    {
        return count($this->getComponents());
    }
    
    public function isTree()
    {
        $isTree = true;
                
        if($this->hasLoops()){
            $isTree = false;
        }
        
        if($this->getComponentsCount() > 1){
            $isTree = false;
        }
        
        if(!$this->isNotMultigraph()){
            $isTree = false;
        }
        
        if($isTree){
            foreach($this->getNodes() as $node){
                $way = $this->existWay($node, $node, true);
                if($way){
                    $isTree = false;
                    break;
                }
            }
        }
        
        return $isTree;
    }
    
    public function getTreeRoot()
    {
        $root = null;
        if($this->isOriented() && $this->isTree()){            
            foreach($this->getNodes() as $node){    
                echo $node;
                if(count($node->getConnectionsIn()) == 0){
                    $root = $node;                    
                    break;
                }
            }
        }
            
        return $root;
    }
    
    private function getStartNode($nodeName)
    {
        $nodes = $this->getNodes();
        $startNode = null;
        foreach($nodes as $node){
            if($node->getName() == trim($nodeName)){
                $startNode = $node;
                break;
            }
        }
        
        return $startNode;
    }
    
    public function deepFirstSearch($nodeName)
    {
        $startNode = $this->getStartNode($nodeName);
        
        if(is_null($startNode))
            throw new GraphException ("Node $nodeName doesnt found");
        
        $result = $this->findNodesExtended(array(), $startNode, $this->isOriented());
        
        return $result;
    }
    
    private function findNodesExtended($uniqueNodes, Node $node, $oriented = false)
    {
        $edges = $oriented ? $node->getConnectionsOut() : $node->getEdges();         
        $found = false;
        foreach ($uniqueNodes as $uniqueNode) {
            if ($uniqueNode == $node) {
                $found = true;
                break;
            }
        }

        if ($found == false)
            $uniqueNodes[] = $node;

        $nodes = array();
        foreach ($edges as $edge) {
            if (!in_array($edge->getNodeB(), $uniqueNodes)) {
                $nodes[] = $edge->getNodeB();
            }
            if ($oriented == false && !in_array($edge->getNodeA(), $uniqueNodes)) {
                $nodes[] = $edge->getNodeA();
            }            
        }
        
        $sorter = new SortNodeByName();
        array_map(array($sorter, 'insert'), $nodes);
        $nodes = iterator_to_array($sorter);        
        
        foreach($nodes as $item){
            $uniqueNodes = $this->findNodesExtended($uniqueNodes, $item, $oriented);
        }

        return $uniqueNodes;
    }    
    
    public function breadthFirstSearch($nodeName)
    {
        $startNode = $this->getStartNode($nodeName);        
        $result = $this->findNodesDeepSearch(array($startNode), $startNode, $this->isOriented());
        
        return $result;
    }
    
    public function findNodesDeepSearch($uniqueNodes, Node $node, $oriented = false, $visited = array())
    {        
        $visited[$node->getName()] = 'open'; 
        $dir = $oriented ? Node::DIR_OUT : Node::DIR_BOTH;        
        $succesors = $node->getNeighbors($dir);
        
        $sorter = new SortNodeByName();
        array_map(array($sorter, 'insert'), $succesors);
        $succesors = iterator_to_array($sorter);        
            
        foreach($succesors as $node){
            if(!in_array($node,$uniqueNodes)){
                $uniqueNodes[] = $node;
            }            
        }
        
        $copy = array_reverse($succesors);
        foreach($copy as $node){
            if(!array_key_exists($node->getName(),$visited)){
                $uniqueNodes = $this->findNodesDeepSearch($uniqueNodes, $node, $oriented, $visited);
            }
        }
        
        $visited[$node->getName()] = 'closed'; 
        return $uniqueNodes;
    }
    
    public function deepFirstSearchInOrder($nodeName)
    {
        $startNode = $this->getStartNode($nodeName);        
        $data = $this->findNodesInOrder(array(), $startNode, $this->isOriented());
        
        return $data;
    }
    
    public function deepFirstSearchPostOrder($nodeName)
    {
        $startNode = $this->getStartNode($nodeName);        
        $data = $this->findNodesPostOrder(array(), $startNode, $this->isOriented());
        
        return $data;
    }    
    
    public function findNodesInOrder($uniqueNodes, Node $node, $oriented = false)
    {
        $dir = $oriented ? Node::DIR_OUT : Node::DIR_BOTH;        
        $succesors = $node->getNeighbors($dir);
        
        if(!empty($succesors[0])){
            $uniqueNodes = $this->findNodesInOrder($uniqueNodes, $succesors[0], $oriented);
        }
        $uniqueNodes[] = $node;
        if(!empty($succesors[1])){
            $uniqueNodes = $this->findNodesInOrder($uniqueNodes, $succesors[1], $oriented);
        }
        
        return $uniqueNodes;
    }
    
    public function findNodesPostOrder($uniqueNodes, Node $node, $oriented = false)
    {
        $dir = $oriented ? Node::DIR_OUT : Node::DIR_BOTH;        
        $succesors = $node->getNeighbors($dir);
        
        if(!empty($succesors[0])){
            $uniqueNodes = $this->findNodesPostOrder($uniqueNodes, $succesors[0], $oriented);
        }        
        if(!empty($succesors[1])){
            $uniqueNodes = $this->findNodesPostOrder($uniqueNodes, $succesors[1], $oriented);
        }
        $uniqueNodes[] = $node;
        
        return $uniqueNodes;
    }    
       
    public function existWay(Node $node, Node $searchNode, $ignoreOrientation = false)
    {        
        $found = false;
        $outConns = $node->getConnectionsOut();
        $inConns = $node->getConnectionsIn();
        
        if(!empty($outConns)){
            foreach($outConns as $edge){
                if(!in_array($edge, $this->tmp, true)){
                    $this->tmp[] = $edge;
                    $next = $edge->getNodeB();
                    if($next == $searchNode){
                        return true;
                    } else {
                        $found = $this->existWay($next, $searchNode, $ignoreOrientation);
                        if($found) break;
                    }                    
                }
            }
        }
        
        if($ignoreOrientation &&!empty($inConns) && !$found){
            foreach($inConns as $edge){
                if(!in_array($edge, $this->tmp, true)){
                    $this->tmp[] = $edge;
                    $next = $edge->getNodeA();
                    if($next == $searchNode){
                        return true;
                    } else {
                        $found = $this->existWay($next, $searchNode, $ignoreOrientation);
                        if($found) break;
                    }                    
                }
            }
        }

        return $found;
    }
    
    public function clrTmp()
    {
        $this->tmp = array();
    }
    
    public function cloneGraph()
    {
        $constructer = new Constructer();
        $parser = new Parser();
        
        $nodes = $this->getNodes();
        $edges = array();
        foreach($nodes as $node){
            $constructer->addNode($parser->parseNode($node->_toString()));
            $itEdges = $node->getEdges();
            foreach($itEdges as $edge){               
                if(!in_array($edge, $edges,true)){                    
                    $edges[] = $edge;
                }
            }
        }
                
        foreach($edges as $edge){
            $constructer->addEdge($parser->parseEdge($edge->_toString()));
        }
        
        return $constructer->getGraph(true);
    }
    
    public function __toString()
    {
        $string = "";
        $nodes = $this->getNodes();
        $edges = array();
        foreach($nodes as $node){
            $string .= (string) $node;
            $itEdges = $node->getEdges();            
            foreach($itEdges as $edge){
                if(!in_array($edge, $edges, true)){
                    $edges[] = $edge;
                }
            }            
        }  
        
        foreach($edges as $edge){
            $string .= (string) $edge;
        }      
        
        return $string;
    }
    
    public function findBridges()
    {
        $components = $this->getComponentsCount();
        $comp = $this->getComponents();                           
        $bridges = array();
        
        $edges = $this->getEdges(true);
                
        foreach($edges as $edge){
            $graph = $this->cloneGraph();
  
            $nodes = $graph->getNodes();
            foreach($nodes as $node){
                $innerEdges = $node->getEdges();

                foreach($innerEdges as $inEdge){
                    $str = $inEdge->getNodeA()->getName().'-'.$inEdge->getNodeB()->getName();
                    if($str == $edge){    
                        $node->removeEdge($inEdge);                        
                        $graph->addRoot($inEdge->getNodeB());                      
                        if($graph->getComponentsCount() > $components){
                            if(!in_array($inEdge, $bridges,true)){
                                $bridges[] = $inEdge;         
                                $comp = $graph->getComponents();
                            }
                        }
                        break;
                        break;
                    }
                }
            }
        }
        
        return $bridges;        
    }
    
    public function getEdges($string = false)
    {
        $nodes = $this->getNodes();
        $edges = array();
        foreach($nodes as $node){
            $nEdges = $node->getEdges();
            foreach($nEdges as $edge){
                if(!in_array($edge, $edges,true)){
                    $edges[] = $edge;
                }
            }
        }
        
        if($string){
            $edgesStr = array();
            foreach($edges as $edge){
                $edgesStr[] = $edge->getNodeA()->getName().'-'.$edge->getNodeB()->getName();
            }
        }
        
        return $string ? $edgesStr : $edges;
    }
    
    public function findArticulations()
    {
        $nodes = $this->getNodes();
        $components = $this->getComponentsCount();
        
        $articulations = array();
        foreach($nodes as $node){
            $graph = $this->cloneGraph();
            $nodesCopy = $graph->getNodes();
            

            foreach($nodesCopy as $item){
                if($item->getName() == $node->getName()){
                    $edges = $item->getEdges();
                                        
                    $neigbors = $item->getNeighbors();
                    foreach($edges as $edge){                        
                        $item->removeEdge($edge);                        
                        $edge->getNodeB()->removeEdge($edge);
                        $edge->getNodeA()->removeEdge($edge);
                                                                
                        if(count($edge->getNodeB()->getConnectionsIn()) == 0 && count($edge->getNodeB()->getConnectionsOut()) == 0){
                            $graph->addRoot($edge->getNodeB());
                        }
                    }
                    foreach($graph->getRoots() as $root){
                        if($root == $item){                            
                            $graph->removeRoot($item);                                                        
                            foreach($neigbors as $nIt){
                                $graph->addRoot($nIt);
                            }
                        }
                    }
                    
                    if($graph->getComponentsCount() > $components){
                        $articulations[] = $node;
                    }
                }
            }            
        }
        
        return $articulations;
    }
    
    public function isNetwork()
    {
        $isNetwork = $this->isOriented() ? true : false;
        
        $nodes = $this->getNodes();
        $sourceFound = false;
        $targetFound = false;        
        foreach($nodes as $node){
            if($node->getName() == "S" || $node->getName() == "s"){
                $sourceFound = true;
                $ins = $node->getConnectionsIn();
                if(count($ins) > 0){
                    $isNetwork = false;
                    break;
                }                
            }
            if($node->getName() == "T" || $node->getName() == "t"){
                $targetFound = true;
                $outs = $node->getConnectionsOut();
                if(count($outs) > 0){
                    $isNetwork = false;
                    break;
                }
            }
        
            $edges = $node->getSurrounding();
            foreach($edges as $edge){
                $value = $edge->getValue();
                if(is_null($value) || $value < 0){
                    $isNetwork = false;
                    break;
                }
            }
                           
        }
        
        if(!($sourceFound && $targetFound)){
            $isNetwork = false;
        }
        
                
        return $isNetwork;
    }
    
}
