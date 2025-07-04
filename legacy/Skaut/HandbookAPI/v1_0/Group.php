<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class Group implements \JsonSerializable
{
    private $name;
    private $count;

    public function __construct(string $name, int $count)
    {
        $this->name = Helper::xssSanitize($name);
        $this->count = $count;
    }

    public function jsonSerialize(): array
    {
        return ['name' => $this->name, 'count' => $this->count];
    }
}
