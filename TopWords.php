<?php

declare(strict_types=1);

class TopWords {
    private string $text;
    private int $count;

    public function __construct(string $text, int $count)
    {
        $this->text = $text;
        $this->count = $count;
    }

    public function list(): array
    {
        return
            $this->sliced(
                $this->sorted(
                    $this->counted(
                        $this->trimmed(
                            $this->lowerCased(
                                $this->spaceExploded(
                                    $this->text
                                )
                            )
                        )
                    )
                )
            );
    }

    private function spaceExploded(string $text): array
    {
        return explode(' ', $text);
    }

    private function lowerCased(array $input): array
    {
        return
            array_map(
                fn (string $word) => mb_strtolower($word),
                $input
            );
    }

    private function trimmed(array $input): array
    {
        return
            array_map(
                fn(string $word) => preg_replace('/\W/u', '', $word),
                $input
            );
    }

    private function counted(array $input): array
    {
        return
            array_reduce(
                $input,
                function (array $carry, string $word) {
                    $carry[$word] = isset($carry[$word]) ? $carry[$word] + 1 : 1;
                    return $carry;
                },
                []
            );
    }

    private function sorted(array $input): array
    {
        arsort($input);
        return $input;
    }

    private function sliced(array $input): array
    {
        return
            array_slice(
                $input,
                0,
                $this->count
            );
    }
}

$result =
    (new TopWords(
        "Hello! This is hello+ test text. Word `Hello` must be on the top. 'Hello' or \"Hello\" may be with quotas.\n But hello5 must not include in HELLo counting. Test test text - the second place.",
        5
    ))
        ->list();

assertEquals(
    [
        'hello' => 6,
        'test' => 3,
        'text' => 2,
        'must' => 2,
        'be' => 2,
    ],
    $result
);

var_dump($result);

function assertEquals(array $expectedResult, array $actualResult) {
    if ($expectedResult != $actualResult) {
        throw new Exception('Assertation failed');
    }
}
