<?php

    $graph = new Graph();
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');
    $x = new Node('X');
    
    $x->connectTo($c, Edge::DIR_ONEWAY);
    $c->connectTo($d, Edge::DIR_ONEWAY);
    $x->connectTo($b, Edge::DIR_ONEWAY);   
    $b->connectTo($a, Edge::DIR_ONEWAY);
    //$x->connectTo($a, Edge::DIR_ONEWAY);
    //$a->connectTo($x);
    //$d->connectTo($x);
    
    $graph->addRoot($a);    
    
    
    $s = $graph->isTree();
    var_dump($s);
    $s = $graph->getTreeRoot();
    echo $s;
    
    exit;
    foreach($s as $node){
        echo $node;
    }
    exit;
    var_dump($s); exit;
    
    

    //$graph->addRoot($d);
    
    $comps = $graph->getComponents();
    foreach($comps as $comp){
        echo '<br/>-----------------<br/>';
        foreach($comp as $i){
            echo $i;
        }
    }
    
    echo '<br/>-----------------<br/>';    
    $comp = $graph->getComponent('D',true);
    foreach($comp['nodes'] as $i){
            echo $i;
    }
    echo '<br/>';
    foreach($comp['edges'] as $i){
            echo $i;
    }
        
    exit;
    //$sec = $graph->findBridges();
    $sec = $graph->findArticulations();
    foreach($sec as $br){
        echo $br;
    } 
    exit;
    
 
    $graph = new Graph();
    
    
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');    
    $e = new Node('E');    
    $f = new Node('F');
    $g = new Node('G');
    $h = new Node('H');
    $i = new Node('I');
    
    $a->connectTo($b, Edge::DIR_ONEWAY);
    //$a->connectTo($b, Edge::DIR_ONEWAY);   
    $b->connectTo($d, Edge::DIR_ONEWAY);
    $d->connectTo($h, Edge::DIR_ONEWAY);
    $b->connectTo($e, Edge::DIR_ONEWAY);
    $a->connectTo($c, Edge::DIR_ONEWAY);
    $c->connectTo($f, Edge::DIR_ONEWAY);
    $c->connectTo($g, Edge::DIR_ONEWAY);
    $f->connectTo($i, Edge::DIR_ONEWAY);
    //$b->connectTo($a, Edge::DIR_ONEWAY);
    
    
    //foreach($a->getEdges() as $t) echo $t; exit;
    
    $graph->addRoot($a);
    $graph->addRoot($i);
                
    $sec = $graph->findArticulations();
    //var_dump($sec);
    foreach($sec as $br){
        echo $br;
    } 
    exit;
    
    echo $graph;
    echo '<br/>';
    echo $sec;
    exit;
    
    $res = $graph->isNotMultigraph();
    
    //$res = $graph->isTree();
    var_dump($res); exit;
    
    $node = $graph->getTreeRoot();
    echo $node->getName();exit;
    
    $component = $graph->getComponent('I');
    echo $graph->getComponentsCount().'<br/>';
    foreach($component as $node){
        echo $node->_toString().'<br/>';
    }
    
    exit;
    /*$graph = new Graph();
    
    
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');    
    $e = new Node('E');    
    $f = new Node('F');
    $g = new Node('G');
    $h = new Node('H');
    $i = new Node('I');
    
    
    $f->connectTo($b, Edge::DIR_ONEWAY);
    
    $b->connectTo($a, Edge::DIR_ONEWAY);
    $b->connectTo($d, Edge::DIR_ONEWAY);
    $d->connectTo($c, Edge::DIR_ONEWAY);
    $d->connectTo($e, Edge::DIR_ONEWAY);
    
    $f->connectTo($g, Edge::DIR_ONEWAY);
    $g->connectTo($i, Edge::DIR_ONEWAY);
    $i->connectTo($h, Edge::DIR_ONEWAY);
    
    $graph->addRoot($f);*/
    
    //$res = $graph->breadthFirstSearch('F');
    //$res = $graph->deepFirstSearch('A');
    $res = $graph->deepFirstSearchInOrder('A');
    //$res = $graph->deepFirstSearchPostOrder('A');
    foreach($res as $node){
        echo $node->_toString().'<br/>';
    }
    
    exit;
    
    /*
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');    
    $e = new Node('E');    
    $f = new Node('F');
    $x = new Node('X');
    
    
    $a->connectTo($b, Edge::DIR_TWOWAY);
    $b->connectTo($e, Edge::DIR_TWOWAY);
    $e->connectTo($f, Edge::DIR_TWOWAY);
    
    $a->connectTo($c, Edge::DIR_TWOWAY);    
    $c->connectTo($d, Edge::DIR_TWOWAY);
    $c->connectTo($x, Edge::DIR_TWOWAY);
     */       
            

    
    /*$edges = $a->getEdges();
    Edge::mergeEdges($edges[0],$edges[1]);
    
    exit;*/
    $g->addRoot($a);
    $g->addRoot($c);

    $s = $g->deepFirstSearch('A');
    //$s = $g->breadthFirstSearch('A');
    foreach($s as $node){
        echo $node->_toString().'</br>';
    }
    exit;
    
    /*
    $sorter = new SortNodeByName();
    array_map(array($sorter, 'insert'), $test);
    $test = iterator_to_array($sorter);
    foreach($test as $t){
        echo $t->getName() . '<br/>';
    }
    var_dump($test); exit;    
    */
    
    $nodes = $g->getSubgraph(array('A','B','C'));
    
    $allEdges = array();
    foreach($nodes as $node){        
        echo $node->_toString().'<br/>';
        $edges = $node->getEdges();
        foreach($edges as $edge){            
            if(!in_array($edge, $allEdges)){                
                $allEdges[] = $edge;
            }
        }
    }
    
    foreach($allEdges as $edge){        
        echo $edge->_toString().'<br/>';
    }      
    
    //var_dump($nodes); 
    exit;
        
    $edges = array();
    foreach($nodes as $node){        
        echo $node->_toString().PHP_EOL;
        $edges = $node->getEdges();
        foreach($edges as $edge){
            if(!in_array($edge, $edges)){
                $edges[] = $edge;
            }
        }
    }
    
    foreach($edges as $edge){        
        echo $edge->_toString().PHP_EOL;
    }     
    
    var_dump($g->isSymetric());
    exit;
    
    /*$a->connectTo($b, Edge::DIR_ONEWAY);    
    $b->connectTo($a, Edge::DIR_ONEWAY);    
    $c->connectTo($a, Edge::DIR_ONEWAY);
    $c->connectTo($b, Edge::DIR_ONEWAY);
    $a->connectTo($c, Edge::DIR_ONEWAY);    
    $b->connectTo($c, Edge::DIR_ONEWAY);  */  
      
    //$a->connectTo($b, Edge::DIR_TWOWAY);
    //$b->connectTo($a, Edge::DIR_TWOWAY);
    //$b->connectTo($c, Edge::DIR_TWOWAY);    
    
    
    //$a->connectTo($a, Edge::DIR_ONEWAY);    
    //$a->connectTo($a, Edge::DIR_ONEWAY);
    
    
    
    //$g->addRoot($c);
    
    
    var_dump($g->isComplete());
    exit;
    echo 'Prosty: ';
    var_dump($g->isNotMultigraph()).PHP_EOL;
    
    echo 'Jednodchy: ';
    var_dump($g->isSimple()).PHP_EOL;


    exit;
    $g = new Graph();
    
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');
    $e = new Node('E');

    $a->connectTo($c, Edge::DIR_ONEWAY, 2);
    $a->connectTo($b, Edge::DIR_ONEWAY, 3);
    $a->connectTo($e, Edge::DIR_ONEWAY, 7);
    $b->connectTo($c, Edge::DIR_ONEWAY, 3);
    $b->connectTo($d, Edge::DIR_ONEWAY, 2);
    $c->connectTo($d, Edge::DIR_ONEWAY, 6);
    $d->connectTo($a, Edge::DIR_ONEWAY, 5);
    $d->connectTo($e, Edge::DIR_ONEWAY, 4);
    $e->connectTo($c, Edge::DIR_ONEWAY, 3);
    $e->connectTo($b, Edge::DIR_ONEWAY, 8);
    
    $g->addRoot($a);
    
    $t = $g->isNotMultigraph();
    $x = $g->isSimple();
    var_dump($t,$x);





    exit;

    $g = new Graph();
        
    $node = new Node('A');   
    $nodeB = new Node('B');    
    $nodeC = new Node('C'); 
    $nodeD = new Node('D'); 
    $g->addRoot($node);
    
    $node->connectTo($nodeB);    
    $node->connectTo($nodeC);                    
    $nodeC->connectTo($nodeB);
    $nodeD->connectTo($node);
    
    
    $subgraph = array('A','B','C');
    
    $nodes = $g->getSubgraph($subgraph);
    $edges = array();
    foreach($nodes as $node){
        echo $node->_toString();
        $edges = $node->getEdges();
        foreach($edges as $edge){
            if(!in_array($edge, $edges)){
                $edges[] =  $edge;
            }
        }
    }
    
    foreach($edges as $edge){
        echo $edge->_toString();
    }
    
    
    
    exit;
    
        
   $parser = new Parser();   
   
   $line = 'h A   - B2   4 :hrana1';
   
   $data = $parser->parseLine($line);
   
   var_dump($data);
    
   
    
    
    exit;

























                    
    //$nodeB->connectTo($node);    
                    
    $r = $g->isComplete();
    var_dump($r); exit;
    
    
    //$g->addRoot($nodeC);
    $r = $g->isRegular();
    $s = $g->getRegularDegree();
    
    //$r = $node->getNodeDegree();
    var_dump($r,$s); exit;
    
    $a=$node->getSurrounding(Node::DIR_BOTH);
    var_dump($a); 
    exit;
    
    
    $n=$node->getNeighbors(Node::DIR_BOTH);
    foreach($n as $i){
        echo $i->getName();
    }
    exit;
    
    
    
    
    $sym = $g->isSymetric();
    var_dump($sym);
    
    exit;
    //$nodes = $g->getNodes();
    //var_dump($nodes); exit;
    
    //$matrix = $g->getAdjacencyMatrix();
    //var_dump($matrix); exit;
    
    $d = $g->isNotMultigraph();
    GDump::d($d);
    $c = $g->isSimple();
    GDump::d($c);
    
    exit;
    
 
    
    
    $a = new Node('A');
    $b = new Node('B');
    $c = new Node('C');
    $d = new Node('D');
    $e = new Node('E');
    $f = new Node('F');
    $g = new Node('G');
    $h = new Node('H');
    $i = new Node('I');
    $j = new Node('J');
    
    $a->connectTo($b, Edge::DIR_ONEWAY, 3);
    $a->connectTo($i, Edge::DIR_ONEWAY, 9);
    $b->connectTo($c, Edge::DIR_ONEWAY, 5);
    $b->connectTo($g, Edge::DIR_ONEWAY, 8);
    $b->connectTo($j, Edge::DIR_ONEWAY, 9);
    $c->connectTo($g, Edge::DIR_ONEWAY, 2);
    $d->connectTo($d, Edge::DIR_ONEWAY, 1);
    $f->connectTo($f, Edge::DIR_ONEWAY, 4);
    $g->connectTo($j, Edge::DIR_ONEWAY, 11);
    $j->connectTo($h, Edge::DIR_ONEWAY, 15);
    $h->connectTo($e, Edge::DIR_ONEWAY, 12);
    $i->connectTo($f, Edge::DIR_ONEWAY, 7);
    
    $graph = new Graph();
    $graph->addRoot($a);
    $graph->addRoot($d);
    
    $loop = $graph->getLoops();
    foreach($loop as $l){
        echo 'A>'.$l->getNodeA()->getName().'<br/>';
        echo 'B>'.$l->getNodeB()->getName().'<br/>';
    }
    exit;
    
    $d = $graph->isNotMultigraph();
    GDump::d($d);
    $c = $graph->isSimple();
    GDump::d($c);
    