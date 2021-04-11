<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class FullField implements \JsonSerializable
{
    private $id;
    private $name;
    private $description;
    private $image;
    private $icon;

    public function __construct(string $id, string $name, $description, string $image, string $icon)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
        $this->image = Uuid::fromBytes($image);
        $this->icon = Uuid::fromBytes($icon);
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getIcon() : UuidInterface
    {
        return $this->icon;
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'image' => $this->image, 'icon' => $this->icon];
    }
}
