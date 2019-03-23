<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class Field extends LessonContainer implements \JsonSerializable
{
    private $id;
    private $name;

    public function __construct(string $id, string $name)
    {
        parent::__construct();
        $this->id = Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'lessons' => $this->lessons];
    }
}
