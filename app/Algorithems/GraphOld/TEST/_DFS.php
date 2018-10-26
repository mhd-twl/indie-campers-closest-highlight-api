<?php
namespace App\Algorithems\Graph;
class DFS{
    protected $graph = [];

    public function __construct($graph)
    {
         $this->graph = $graph;
    }
    public function IDDFS($root, $goal) {
        $depth = 0;

        while ($depth <= 2) { // 2 is hard-coded for now
            $result = $this->DLS($root, $goal, $depth);

            if ($result !== false) {
                return $result;
            }

            $depth++;
        }
    }

    public function DLS($node, $goal, $depth) {
        global $graph;

        if (($depth >= 0) && ($node == $goal)) {
            return $node;
        }

        else if ($depth > 0) {
            foreach ($this->expand($node, $this->graph) as $child) {
                return $this->DLS($child, $goal, $depth - 1);
            }
        }

        else {
            return false;
        }
    }
    public function directed2Undirected($data) {
        foreach ($data as $key => $values) {
            foreach ($values as $value) {
                $data[$value][] = $key;
            }
        }

        return $data;
    }

    public function expand($id, $data, $depth = 0) {
        while (--$depth >= 0) {
            $id = array_flatten(array_intersect_key($data, array_flip((array) $id)));
        }
        return array_unique(array_flatten(array_intersect_key($data, array_flip((array) $id))));
    }

    // public function flatten($data) {
    //     $result = array();

    //     if (is_array($data) === true) {
    //         foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($data)) as $value) {
    //             $result[] = $value;
    //         }
    //     }

    //     return $result;
    // }
}