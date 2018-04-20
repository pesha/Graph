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
    
    $stdout = fopen("php://stdout", "w");
    $graph = $constructer->getGraph();
    
    $alg = new JarnikSkeleton($graph);
    $skeleton = $alg->getSkeleton();

    foreach($skeleton['edges'] as $edge){
        fputs($stdout, $edge->_toString().PHP_EOL);
    }
    
    fputs($stdout, "#!value=".$alg->getTotalValue().PHP_EOL);

