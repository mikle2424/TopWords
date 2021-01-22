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
                $this->counted(
                    $this->trimmed(
                        $this->lowercased(
                            $this->spaceExploded()
                        )
                    )
                )
            );
    }

    private function spaceExploded()
    {
        return explode(' ', $this->text);
    }

    private function lowercased(array $input)
    {
        return
            array_map(
                fn (string $word) => mb_strtolower($word),
                $input
            );
    }

    private function trimmed(array $input)
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
                    $carry[$word]++;
                    return $carry;
                },
                []
            );
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

var_dump(
    (new TopWords(
        "Hello! This is hello+ test text. Word `Hello` must be on the top. 'Hello' or \"Hello\" may be with quotas.\n But hello5 must not include in HELLo counting. Test text - the second place.",
        5
    ))
        ->list()
);
