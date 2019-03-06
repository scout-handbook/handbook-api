<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class FullField implements \JsonSerializable
{
    private $id;
    private $name;
    private $description;
    private $image;

    public function __construct(string $id, string $name, $description, string $image)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
        $this->image = Uuid::fromBytes($image);
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'image' => $this->image];
    }
}
