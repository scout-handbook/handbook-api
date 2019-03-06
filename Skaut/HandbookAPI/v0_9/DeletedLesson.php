<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class DeletedLesson implements \JsonSerializable
{
    private $id;
    private $name;

    public function __construct(string $id, string $name)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
