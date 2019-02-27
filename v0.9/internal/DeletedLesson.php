<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Helper;

class DeletedLesson implements \JsonSerializable
{
    public $id;
    public $name;

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
