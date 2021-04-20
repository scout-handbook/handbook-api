<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

class Competence implements \JsonSerializable
{
    private $number;
    private $name;
    private $description;

    public function __construct(int $number, string $name, string $description)
    {
        $this->number = $number;
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
    }

    public function jsonSerialize() : array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
