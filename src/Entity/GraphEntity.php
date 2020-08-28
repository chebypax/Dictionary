<?php

namespace App\Entity;

use App\Service\DataConverter;

class GraphEntity
{
    /**
     * @var array
     */
    private $dictionary;
    private $adjacencyList;

    public function __construct(array $dictionary)
    {
        $this->dictionary = $dictionary;
        $this->adjacencyList = [];
        $this->generateGraph();
    }

    public function shortestWay(string $start, string $finish): array
    {
        $path = [];
        $distances = [];
        $previous = [];
        $pq = new PriorityQueueEntity();
        $list =& $this->adjacencyList;
        foreach ($list as $key => $value) {
            $dist = $key == $start ? 0 : INF;
            $distances[$key] = $dist;
            $previous[$key] = null;
            $pq->enqueue($key, $dist);
        }

        while (count($pq->queue) > 0) {
            $smallestNode = $pq->dequeue()->value;
            if ($smallestNode === $finish && $distances[$smallestNode] !== INF) {
                while ($previous[$smallestNode]) {
                    $path[] = $smallestNode;
                    $smallestNode = $previous[$smallestNode];
                }
                $path[] = $start;

                return array_reverse($path);
            }

            if ($smallestNode) {
                foreach ($list[$smallestNode] as $item) {
                    $nextNode = $item;
                    $newDistance = $distances[$smallestNode] + 1;
                    if ($newDistance < $distances[$nextNode]) {
                        $distances[$nextNode] = $newDistance;
                        $previous[$nextNode] = $smallestNode;
                        $pq->enqueue($nextNode, $newDistance);
                    }
                }
            }
        }

        return $path;
    }

    private function generateGraph()
    {
        foreach ($this->dictionary as $word) {
            for ($i = 0; $i < strlen($word); $i += 2) {
                $letter = DataConverter::CHARACTER_KEYS['-'];
                while ($letter <= DataConverter::CHARACTER_KEYS['я']) {
                    $newWord = substr_replace($word, $letter, $i, 2);
                    if ($this->binarySearch($newWord)) {
                        $this->addVertex($newWord);
                        $this->addEdge($word, $newWord);
                    }
                    $letter += 1;
                }
            }
        }
    }


    private function addVertex(string $vertex)
    {
        if (!array_key_exists($vertex, $this->adjacencyList)) {
            $this->adjacencyList[$vertex] = [];
        }
    }

    private function addEdge(string $vertex1, string $vertex2): void
    {
        if ($vertex1 === $vertex2) {
            return;
        }
        if (!array_key_exists($vertex1, $this->adjacencyList) || !array_key_exists($vertex2, $this->adjacencyList)) {
            return;
        }
        if (!in_array($vertex2, $this->adjacencyList[$vertex1])) {
            $this->adjacencyList[$vertex1][] = $vertex2;
        }
        if (!in_array($vertex1, $this->adjacencyList[$vertex2])) {
            $this->adjacencyList[$vertex2][] = $vertex1;
        }
    }

    private function binarySearch(string $value): bool
    {
        $arr = $this->dictionary;
        if (count($arr) === 0) return false;
        $low = 0;
        $high = count($arr) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);

            if ($arr[$mid] == $value) {
                return true;
            }

            if ($value < $arr[$mid]) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return false;
    }
}
