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
    $isTree = $graph->isTree();
    $resString = $isTree ? 'true' : 'false';
    
    $stdout = fopen("php://stdout", "w");
    fputs($stdout, '#!isTree='.$resString.PHP_EOL);
    
    if($isTree && $graph->isOriented()){
        $root = $graph->getTreeRoot();
        fputs($stdout, '#!root='.$root->_toString().PHP_EOL);
    }