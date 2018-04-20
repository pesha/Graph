<?php

/**
 * Description of Parser
 *
 * @author Martin
 */
class Parser
{

    public function parseLine($line)
    {
        $data = null;
        if(preg_match('/^u/i', $line) == 1){
            $data = $this->parseNode($line);
            $data['type'] = 'node';
        } elseif(preg_match('/^h/i', $line) == 1) {
            $data = $this->parseEdge($line);
            $data['type'] = 'edge';
        } elseif(preg_match('/^#/i', $line) == 1) {
            return array('type' => 'comment');
        } else {
            //throw new Exception('Parse error on line'.$line);
        }
        
        return $data;
    }
    
    public function parseNode($line)
    {
        preg_match('/^u (\w+)( +(-?\d+))?/i', $line, $matches);

        $data = array(
            'name' => $matches[1],
        );
        
        if(!empty($matches[3]))
            $data['value'] = $matches[3];
        
        return $data;
    }
    
    public function parseEdge($line)
    {
       preg_match(' /^h (\w+) +(-|>) +(\w+)( +(-?\d+))?( +:(.+))?/i', $line, $matches);
       
       $data = array(
           'nodeA' => $matches[1],
           'direction' => $matches[2],
           'nodeB' => $matches[3],           
       );
       
       if(!empty($matches[5])) $data['value'] = $matches[5]; 
       if(!empty($matches[7])) $data['name'] = $matches[7]; 
       
       return $data;
    }
    
    
}