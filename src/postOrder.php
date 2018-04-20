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
        
    $nodeName = trim($argv[1]);
    
    $data = $graph->deepFirstSearchPostOrder($nodeName);    
    
    $stdout = fopen("php://stdout", "w");
    
    foreach($data as $node){
        fputs($stdout, $node->_toString().PHP_EOL);
    }
