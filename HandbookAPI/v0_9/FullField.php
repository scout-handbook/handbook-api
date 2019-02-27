<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

require($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Helper.php');

use Ramsey\Uuid\Uuid;

class FullField implements \JsonSerializable
{
    public $id;
    public $name;
    public $description;
    public $image;

    public function __construct(string $id, string $name, $description, string $image)
    {
        $this->id = Uuid::fromBytes($id);
        $this->name = \HandbookAPI\Helper::xssSanitize($name);
        $this->description = \HandbookAPI\Helper::xssSanitize($description);
        $this->image = Uuid::fromBytes($image);
    }

    public function jsonSerialize() : array
    {
        return ['id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'image' => $this->image];
    }
}
