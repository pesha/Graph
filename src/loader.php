<?php

/**
 * Loader
 *
 * @author Martin Péchal
 */
class Loader
{

    const GRAPH_FOLDER = "lib/Graph/";

    public $files = array(
        'Edge.php',
        'Node.php',
        'Graph.php',
        'Path.php',
        'GraphException.php',
        'SortNodeByName.php',
        'Diagnostics/GDebug.php',
        'AdjacencyMatrix.php',
        'Parser/Parser.php',
        'Parser/Constructer.php',
        'Interfaces/ISkeleton.php',
        'Algorithms/JarnikSkeleton.php',
        'Algorithms/KruskalSkeleton.php',
        'Algorithms/DijkstrOptimalPath.php',
        'Algorithms/DijkstrOptimalPath2.php',
        'Algorithms/BellmanFordOptimalPath.php',
    );

    public function load()
    {
        foreach($this->files as $file){
            require_once self::GRAPH_FOLDER . $file;
        }
    }


}
