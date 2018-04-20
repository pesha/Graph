<?php

    define("SRC_FOLDER","/src/");

    require_once SRC_FOLDER . 'loader.php';

    $loader = new Loader();
    $loader->load();
    
    require_once SRC_FOLDER . 'run.php';
