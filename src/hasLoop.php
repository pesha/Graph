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
    $result = $graph->hasLoops();
    $resString = $result ? 'true' : 'false';
    
    $stdout = fopen("php://stdout", "w");
    fputs($stdout, '#!hasLoop='.$resString.PHP_EOL);
    
    $loops = $graph->getLoops();
    foreach($loops as $loop){
        fputs($stdout, $loop->_toString().PHP_EOL);    
    }
    