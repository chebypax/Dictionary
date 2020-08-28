<?php

namespace App\Entity;

class QueueNodeEntity
{
    /**
     * @var string
     */
    public $value;
    /**
     * @var int
     */
    public $priority;

    public function __construct(string $value, float $priority)
    {

        $this->value = $value;
        $this->priority = $priority;
    }
}
