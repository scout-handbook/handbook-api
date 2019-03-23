<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class Field implements \JsonSerializable
{
    private $name;
    private $description;
    private $image;
    private $lessons;

    public function __construct(string $name, $description, string $image)
    {
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
        $this->image = Uuid::fromBytes($image);
        $this->lessons = [];
    }

    public function addLesson(string $lesson) : void
    {
        $this->lessons[] = Uuid::fromBytes($lesson);
    }

    public function jsonSerialize() : array
    {
        return ['name' => $this->name, 'description' => $this->description, 'image' => $this->image, 'lessons' => $this->lessons];
    }
}
