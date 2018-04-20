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
       
    $bridges = $graph->findBridges();
    
    $stdout = fopen("php://stdout", "w");
    
    foreach($bridges as $edge){
        fputs($stdout, $edge->_toString().PHP_EOL);
    }