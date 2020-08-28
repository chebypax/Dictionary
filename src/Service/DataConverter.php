<?php

namespace App\Service;

class DataConverter
{
    const CHARACTER_KEYS = [
        '-' => 10,
        'а' => 11,
        'б' => 12,
        'в' => 13,
        'г' => 14,
        'д' => 15,
        'е' => 16,
        'ё' => 16,
        'ж' => 18,
        'з' => 19,
        'и' => 20,
        'й' => 21,
        'к' => 22,
        'л' => 23,
        'м' => 24,
        'н' => 25,
        'о' => 26,
        'п' => 27,
        'р' => 28,
        'с' => 29,
        'т' => 30,
        'у' => 31,
        'ф' => 32,
        'х' => 33,
        'ц' => 34,
        'ч' => 35,
        'ш' => 36,
        'щ' => 37,
        'ъ' => 38,
        'ы' => 39,
        'ь' => 40,
        'э' => 41,
        'ю' => 42,
        'я' => 43,
    ];

    public function convertWordIntoInt(string $word): string
    {
        $result = '';
        $word = mb_strtolower($word);
        for ($i = 0; $i < mb_strlen($word); $i++) {
            $converted = $this->convertLetterIntoInt(mb_substr($word, $i, 1));
            $result = $converted ? $result.$converted : $result;
        }

        return $result;
    }

    public function convertIntIntoWord(string $number): string
    {
        $result = '';
        $number = (string) $number;
        for ($i = 0; $i < strlen($number); $i += 2) {
            $result = $result.$this->convertIntIntoLetter(substr($number, $i, 2));
        }

        return $result;
    }

    private function convertLetterIntoInt(string $letter): string
    {
        return array_key_exists($letter, self::CHARACTER_KEYS) ? self::CHARACTER_KEYS[$letter] : '';
    }

    private function convertIntIntoLetter(string $number): string
    {
        $array = array_filter(self::CHARACTER_KEYS, function ($item) use ($number) {
            return $item === (int) $number;
        });

        return array_key_first($array);
    }
}
