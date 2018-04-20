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
    
    $graph = $constructer->getGraph();
    $nodes = $graph->getNodes();
    
    $nodeName = trim($argv[1]);
    $dir = Node::DIR_BOTH;
    if(!empty($argv[2])){
        switch(trim($argv[2])){
            case Node::DIR_IN: $dir = Node::DIR_IN; break;
            case Node::DIR_OUT: $dir = Node::DIR_OUT; break;
        }    
    }
    
    $stdout = fopen("php://stdout", "w");
    
    foreach($nodes as $node){
        if($node->getName() == $nodeName){            
            $next = $node->getSurrounding($dir);
            foreach($next as $edge){
                fputs($stdout, $edge->_toString().PHP_EOL);
            }
        }
    }