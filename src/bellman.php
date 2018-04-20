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
    
    $start = trim($argv[1]);
    $end = trim($argv[2]);
    
    $alg = new BellmanFordOptimalPath($graph);
    $res = $alg->findOptimalPath($start, $end);
    $res->constructPath();
    
    $stdout = fopen("php://stdout", "w");
    
    if(!$alg->isOk()){        
        fputs($stdout, "#!error={Nalezen cyklus zaporne delky}".PHP_EOL);            
    } else if($res->pathFound()){
        $steps = $alg->getSteps();
        foreach($steps as $k => $stepAll){
            foreach($stepAll as $step)
                fputs($stdout, "#!step ".$k." u=(".$step['edges'].','.$step['lastNode'].','.$step['length'].") ".implode(',', $step['updated']).PHP_EOL);        
        }
                
        fputs($stdout, "#!value=".$res->getLength().PHP_EOL);    
        $string = $res->__toString();
        fputs($stdout, $string);    
        
    } else {
        fputs($stdout, "#!pathNotExists".PHP_EOL);    
    }
    
    