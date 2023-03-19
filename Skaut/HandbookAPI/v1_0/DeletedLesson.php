<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

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
