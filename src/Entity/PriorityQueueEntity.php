<?php


namespace App\Entity;


class PriorityQueueEntity
{
    public $queue;

    public function __construct()
    {
        $this->queue = [];
    }

    public function enqueue($value, $priority): PriorityQueueEntity
    {
        $newNode = new QueueNodeEntity($value, $priority);
        $this->queue[] = $newNode;
        $index = count($this->queue) - 1;
        $this->bubbleUp($newNode, $index);

        return $this;
    }

    private function bubbleUp($newNode, $index)
    {
        $parentIndex = floor(($index - 1) / 2);
        if ($index > 0 && $this->queue[$parentIndex]->priority > $newNode->priority) {
            $this->queue[$index] = $this->queue[$parentIndex];
            $this->queue[$parentIndex] = $newNode;
            $this->bubbleUp($newNode, $parentIndex);
        }

        return true;
    }

    public function dequeue(): ?QueueNodeEntity
    {
        if (count($this->queue) === 0) {
            return null;
        }
        $queue =& $this->queue;
        $minItem = $queue[0];
        if (count($queue) === 1) {
            array_pop($queue);
        } else {
            $this->queue[0] = array_pop($queue);
        }
        $this->sinkDown(0);

        return $minItem;
    }

    private function sinkDown($index)
    {
        $leftChildIndex = $index * 2 + 1;
        $rightChildIndex = $index * 2 + 2;
        $queue =& $this->queue;
        if ($leftChildIndex >= count($this->queue)) {
            return true;
        }
        $minIndex = $leftChildIndex;
        if ($rightChildIndex < count($queue) && $queue[$rightChildIndex]->priority < $queue[$leftChildIndex]->priority) {
            $minIndex = $rightChildIndex;
        }
        if ($queue[$index]->priority < $queue[$minIndex]->priority) {
            return true;
        }
        $tmp = $queue[$minIndex];
        $queue[$minIndex] = $queue[$index];
        $queue[$index] = $tmp;

        $this->sinkDown($minIndex);
    }
}
