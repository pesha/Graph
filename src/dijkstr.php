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
    
    $alg = new DijkstrOptimalPath($graph);
    $res = $alg->findOptimalPath($start, $end);
    
    $stdout = fopen("php://stdout", "w");
    
    if(!$alg->checkConditions()){        
        fputs($stdout, "#!error={Nalezeny zaporne hrany}".PHP_EOL);
    } elseif(!$res) {       
        fputs($stdout, "#!pathNotExists".PHP_EOL);
    } else {        
        $steps = $alg->getSteps();
        
        foreach($steps as $i => $step){
            fputs($stdout, "#!step ".$i."=".$step[0]." (".$step[1].",".$step[2].")".PHP_EOL);
        }
        
        fputs($stdout, "#!value=".$res->getLength().PHP_EOL);
        fputs($stdout, $res);
    }
    
    