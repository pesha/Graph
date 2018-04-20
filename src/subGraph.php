<?php

    
    define("SRC_FOLDER","/");

    require_once 'loader.php';

    $loader = new Loader();
    $loader->load();
    
    $parser = new Parser();
    $constructer = new Constructer();
        
    while($line = fgets(STDIN)){
        $data = $parser->parseLine($line);
        
        if($data['type'] == 'node'){
            $constructer->addNode($data);
        }
        
        if($data['type'] == 'edge'){
            $constructer->addEdge($data);
        }        
    }
    
    $graph = $constructer->getGraph(true);
    

    $numargs = count($argv);
    
    $subgraph = array();
    for($i = 1; $i < $numargs; $i++){
        $subgraph[] = $argv[$i];
    }
        
    $nodes = $graph->getSubgraph($subgraph);    
    
    $stdout = fopen("php://stdout", "w");    
    $allEdges = array();
    foreach($nodes as $node){        
        fputs($stdout, $node->_toString().PHP_EOL);
        $edges = $node->getEdges();
        foreach($edges as $edge){            
            if(!in_array($edge, $allEdges)){                
                $allEdges[] = $edge;
            }
        }
    }
    
    foreach($allEdges as $edge){        
        fputs($stdout, $edge->_toString().PHP_EOL);
    }  