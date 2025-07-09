<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or exit('Restricted access.');

class DeletedLesson implements \JsonSerializable
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = Helper::xssSanitize($name);
    }

    public function jsonSerialize(): array
    {
        return ['name' => $this->name];
    }
}
