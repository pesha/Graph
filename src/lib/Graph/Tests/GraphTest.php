<?php

require '../Graph.php';
require '../Node.php';
require '../AdjacencyMatrix.php';
require '../Edge.php';
require '../SortNodeByName.php';

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-16 at 20:15:49.
 */
class GraphTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Graph
     */
    protected $object;
    protected $roots;
    protected $nodes;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getTestGraph2();
        $this->roots = $this->getTestGraph2('roots');
        $this->nodes = $this->getTestGraph2('nodes');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Graph:isSymetric
     */
    public function testIsSymetric()
    {
        $symetric = $this->object->isSymetric();

        $this->assertFalse($symetric);
    }

    /**
     * @covers Graph::getNodes
     */
    public function testGetNodes()
    {
        $nodes = $this->object->getNodes();

        $found = array();
        foreach ($this->nodes as $node) {
            foreach ($nodes as $search) {
                if ($node === $search) {
                    $found[] = $node;
                }
            }
        }

        $exist = true;
        foreach ($nodes as $node) {
            if (!in_array($node, $found))
                $exist = false;
        }

//        $this->assertTrue($exist);
//        $this->assertEquals($this->nodes, $found);
    }

    /**
     * @covers Graph::getRoots
     */
    public function testGetRoots()
    {
        $roots = $this->object->getRoots();
        $this->assertEquals($roots, $this->roots);
    }

    /**
     * @covers Graph::getLoops
     */
    public function testGetLoops()
    {
        $right = array('F' => 'F', 'D' => 'D');
        $loops = $this->object->getLoops();
        $found = array();
        foreach ($loops as $edge) {
            $found[$edge->getNodeA()->getName()] = $edge->getNodeB()->getName();
        }

        $this->assertEquals($right, $found);
    }

    /**
     * @covers Graph::hasLoops
     */
    public function testHasLoops()
    {
        $this->assertTrue($this->object->hasLoops());
    }

    /**
     * @covers Graph::isNotMultigraph     
     */
    public function testIsNotMultigraph()
    {
        $this->assertTrue($this->object->isNotMultigraph());
    }

    /**
     * @covers Graph::isSimple     
     */
    public function testIsSimple()
    {

        $this->assertFalse($this->object->isSimple());
    }
    
    /**
     * @covers Graph::deepFirstSearch
     */
    public function testDeepFirstSearch()
    {
        $graph = new Graph();

        $a = new Node('A');
        $b = new Node('B');
        $c = new Node('C');
        $d = new Node('D');    
        $e = new Node('E');    
        $f = new Node('F');
        $g = new Node('G');
        $h = new Node('H');
        $i = new Node('I');

        $a->connectTo($b, Edge::DIR_ONEWAY);
        $b->connectTo($d, Edge::DIR_ONEWAY);
        $d->connectTo($h, Edge::DIR_ONEWAY);
        $b->connectTo($e, Edge::DIR_ONEWAY);
        $a->connectTo($c, Edge::DIR_ONEWAY);
        $c->connectTo($f, Edge::DIR_ONEWAY);
        $c->connectTo($g, Edge::DIR_ONEWAY);
        $f->connectTo($i, Edge::DIR_ONEWAY);

        $graph->addRoot($a);

        $nodes = array();
        $res = $graph->deepFirstSearch('A');
        foreach($res as $node){
            $nodes[] = $node->getName();
        }
        
        $correctNodes = array('A','B','D','H','E','C','F','I','G');                        
        $diff = array_diff_assoc($nodes,$correctNodes);        
        $this->assertEquals(count($diff),0);   
    }
    
    /**
     * @covers Graph::deepFirstSearchPostOrder
     */
    public function testDeepFirstSearchPostOrder()
    {
        $graph = new Graph();

        $a = new Node('A');
        $b = new Node('B');
        $c = new Node('C');
        $d = new Node('D');    
        $e = new Node('E');    
        $f = new Node('F');
        $g = new Node('G');
        $h = new Node('H');
        $i = new Node('I');

        $a->connectTo($b, Edge::DIR_ONEWAY);
        $b->connectTo($d, Edge::DIR_ONEWAY);
        $d->connectTo($h, Edge::DIR_ONEWAY);
        $b->connectTo($e, Edge::DIR_ONEWAY);
        $a->connectTo($c, Edge::DIR_ONEWAY);
        $c->connectTo($f, Edge::DIR_ONEWAY);
        $c->connectTo($g, Edge::DIR_ONEWAY);
        $f->connectTo($i, Edge::DIR_ONEWAY);

        $graph->addRoot($a);

        $nodes = array();
        $res = $graph->deepFirstSearchPostOrder('A');
        foreach($res as $node){
            $nodes[] = $node->getName();
        }
        
        $correctNodes = array('H','D','E','B','I','F','G','C','A');                        
        $diff = array_diff_assoc($nodes,$correctNodes);        
        $this->assertEquals(count($diff),0);
    }   
    
    /**
     * @cover Graph::deepFirstSearchInOrder
     */
    public function testDeepFirstSearchInOrder()
    {
        $graph = new Graph();

        $a = new Node('A');
        $b = new Node('B');
        $c = new Node('C');
        $d = new Node('D');    
        $e = new Node('E');    
        $f = new Node('F');
        $g = new Node('G');
        $h = new Node('H');
        $i = new Node('I');

        $a->connectTo($b, Edge::DIR_ONEWAY);
        $b->connectTo($d, Edge::DIR_ONEWAY);
        $d->connectTo($h, Edge::DIR_ONEWAY);
        $b->connectTo($e, Edge::DIR_ONEWAY);
        $a->connectTo($c, Edge::DIR_ONEWAY);
        $c->connectTo($f, Edge::DIR_ONEWAY);
        $c->connectTo($g, Edge::DIR_ONEWAY);
        $f->connectTo($i, Edge::DIR_ONEWAY);

        $graph->addRoot($a);

        $nodes = array();
        $res = $graph->deepFirstSearchINOrder('A');
        foreach($res as $node){
            $nodes[] = $node->getName();
        }
        
        $correctNodes = array('H','D','B','E','A','I','F','C','G');                        
        $diff = array_diff_assoc($nodes,$correctNodes);        
        $this->assertEquals(count($diff),0);
    }     

    public function getTestGraph2($what = 'all')
    {

        $graph = new Graph;

        $a = new Node('A');
        $b = new Node('B');
        $c = new Node('C');
        $d = new Node('D');
        $e = new Node('E');
        $f = new Node('F');
        $g = new Node('G');
        $h = new Node('H');
        $i = new Node('I');
        $j = new Node('J');

        $a->connectTo($b, Edge::DIR_ONEWAY, 3);
        $a->connectTo($i, Edge::DIR_ONEWAY, 9);
        $b->connectTo($c, Edge::DIR_ONEWAY, 5);
        $b->connectTo($g, Edge::DIR_ONEWAY, 8);
        $b->connectTo($j, Edge::DIR_ONEWAY, 9);
        $c->connectTo($g, Edge::DIR_ONEWAY, 2);
        $d->connectTo($d, Edge::DIR_ONEWAY, 1);
        $f->connectTo($f, Edge::DIR_ONEWAY, 4);
        $g->connectTo($j, Edge::DIR_ONEWAY, 11);
        $j->connectTo($h, Edge::DIR_ONEWAY, 15);
        $h->connectTo($e, Edge::DIR_ONEWAY, 12);
        $i->connectTo($f, Edge::DIR_ONEWAY, 7);

        $graph->addRoot($a);
        $graph->addRoot($d);

        // manual test
        if ($what == 'all') {
            $return = $graph;
        } elseif ($what == 'roots') {
            $roots = array();
            $roots[$a->getName()] = $a;
            $roots[$d->getName()] = $d;

            $return = $roots;
        } elseif ($what == 'nodes') {
            $return = array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j);
        }

        return $return;
    }

    /**
     * 
     * @return Graph
     */
    public function getTestGraph1()
    {
        $g = new Graph();

        $a = new Node('A');
        $b = new Node('B');
        $c = new Node('C');
        $d = new Node('D');
        $e = new Node('E');

        $a->connectTo($c, Edge::DIR_ONEWAY, 2);
        $a->connectTo($b, Edge::DIR_ONEWAY, 3);
        $a->connectTo($e, Edge::DIR_ONEWAY, 7);
        $b->connectTo($c, Edge::DIR_ONEWAY, 3);
        $b->connectTo($d, Edge::DIR_ONEWAY, 2);
        $c->connectTo($d, Edge::DIR_ONEWAY, 6);
        $d->connectTo($a, Edge::DIR_ONEWAY, 5);
        $d->connectTo($e, Edge::DIR_ONEWAY, 4);
        $e->connectTo($c, Edge::DIR_ONEWAY, 3);
        $e->connectTo($b, Edge::DIR_ONEWAY, 8);

        $g->addRoot($a);

        return $graph;
    }

}