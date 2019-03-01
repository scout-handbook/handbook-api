<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

require($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');

use Ramsey\Uuid\Uuid;

class Competence implements \JsonSerializable
{
    public $id;
    public $number;
    public $name;
    public $description;

    public function __construct(string $id, int $number, string $name, string $description)
    {
        $this->id = Uuid::fromBytes($id);
        $this->number = $number;
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
