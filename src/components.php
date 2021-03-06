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
            
    $stdout = fopen("php://stdout", "w");
    
    if(empty($argv[1])){    
        $components = $graph->getComponentsCount();
        fputs($stdout, '#!components='.$components.PHP_EOL);
    } else {
        $component = $graph->getComponent(trim($argv[1]),true);

        foreach($component['nodes'] as $node){
            fputs($stdout, $node->_toString().PHP_EOL);           
        }
        foreach($component['edges'] as $edge){
            fputs($stdout, $edge->_toString().PHP_EOL);           
        }
    }