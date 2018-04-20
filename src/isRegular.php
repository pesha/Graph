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
    $result = $graph->isRegular();
    $resString = $result ? 'true' : 'false';
    if($result == 'none')
        $resString = 'none';
    
    $stdout = fopen("php://stdout", "w");
    fputs($stdout, '#!isRegular='.$resString.PHP_EOL);
    
    if($result === true){
        $degree = $graph->getRegularDegree();
        fputs($stdout, '#!regularDegree='.$degree.PHP_EOL);
    } 