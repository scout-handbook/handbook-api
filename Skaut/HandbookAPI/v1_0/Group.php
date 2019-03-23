<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class Group implements \JsonSerializable
{
    private $id;
    private $name;
    private $count;

    public function __construct(string $id, string $name, int $count)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->count = $count;
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'count' => $this->count];
    }
}
