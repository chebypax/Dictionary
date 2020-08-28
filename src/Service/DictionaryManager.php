<?php

namespace App\Service;

class DictionaryManager
{
    private $path;
    private $modifiedDictionary;
    /**
     * @var DataConverter
     */
    private $dataConverter;

    public function __construct($path, DataConverter $dataConverter)
    {
        $this->path = $path;
        $this->dataConverter = $dataConverter;
    }

    public function setModifiedDictionary(int $wordLength = 0): void
    {
        $dictionary = file($this->path);
        $result = $wordLength ? [] : $dictionary;
        if (0 !== $wordLength) {

            $dictionary = array_filter($dictionary, function ($word) use ($wordLength) {
                $word = str_replace(array("\r\n", "\r", "\n"), '', $word);

                return mb_strlen($word) === $wordLength;
            });
        }
        foreach ($dictionary as $word) {
            $result[] = $this->dataConverter->convertWordIntoInt($word);
        }
        sort($result);
        $this->modifiedDictionary = $result;
    }

    /**
     * @return mixed
     */
    public function getModifiedDictionary(): array
    {
        return $this->modifiedDictionary;
    }

}
