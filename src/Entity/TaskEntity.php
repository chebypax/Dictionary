<?php

namespace App\Entity;

class TaskEntity
{
    private $firstWord;
    private $secondWord;

    /**
     * @return mixed
     */
    public function getFirstWord()
    {
        return $this->firstWord;
    }

    /**
     * @param mixed $firstWord
     */
    public function setFirstWord($firstWord): void
    {
        $this->firstWord = $firstWord;
    }

    /**
     * @return mixed
     */
    public function getSecondWord()
    {
        return $this->secondWord;
    }

    /**
     * @param mixed $secondWord
     */
    public function setSecondWord($secondWord): void
    {
        $this->secondWord = $secondWord;
    }
}
