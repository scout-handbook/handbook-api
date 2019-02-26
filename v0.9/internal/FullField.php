<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Helper.php');

class FullField implements \JsonSerializable
{
    public $id;
    public $name;
    public $description;
    public $image;

    public function __construct(string $id, string $name, $description, string $image)
    {
        $this->id = \Ramsey\Uuid\Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->description = Helper::xssSanitize($description);
        $this->image = \Ramsey\Uuid\Uuid::fromBytes($image);
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'image' => $this->image];
    }
}
