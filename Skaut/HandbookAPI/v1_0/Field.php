<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Field implements \JsonSerializable
{
    private $name;
    private $description;
    private $image;
    private $icon;
    private $lessons;

    public function __construct(string $name, $description, string $image, string $icon)
    {
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
        $this->image = Uuid::fromBytes($image);
        $this->icon = Uuid::fromBytes($icon);
        $this->lessons = [];
    }

    public function addLesson(string $lesson): void
    {
        $this->lessons[] = Uuid::fromBytes($lesson);
    }

    public function containsLesson(UuidInterface $id): bool
    {
        foreach ($this->lessons as $lesson) {
            if ($lesson->equals($id)) {
                return true;
            }
        }
        return false;
    }

    public function getIcon(): UuidInterface
    {
        return $this->icon;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'icon' => $this->icon,
            'lessons' => $this->lessons
        ];
    }
}
