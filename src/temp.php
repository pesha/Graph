<?php

    define("SRC_FOLDER","/");

    require_once 'loader.php';

    $loader = new Loader();
    $loader->load();
    
    $parser = new Parser();
    $constructer = new Constructer();
    
    $handle = fopen('graf1.txt','r');
    
    while($line = fgets($handle)){
        $data = $parser->parseLine($line);
        
        if($data['type'] == 'node'){
            $constructer->addNode($data);
        }
        
        if($data['type'] == 'edge'){
            $constructer->addEdge($data);
        }        
    }
    
    $graph = $constructer->getGraph();
        
    $result = $graph->isNotMultigraph();
    $resString = $result ? 'true' : 'false';
    
    $stdout = fopen("php://stdout", "w");
    fputs($stdout, '#!isNotMultigraph='.$resString.PHP_EOL);
    echo '#!isNotMultigraph='.$resString.PHP_EOL;