<?php declare(strict_types=1);
namespace HandbookAPI;

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');

require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Helper;

@_API_EXEC === 1 or die('Restricted access.');

class User implements \JsonSerializable
{
    public $id;
    public $name;
    public $role;
    public $groups;

    public function __construct(int $id, string $name, string $role)
    {
        $this->id = $id;
        $this->name = Helper::xssSanitize($name);
        $this->role = new Role($role);
        $this->groups = [];
    }

    public function jsonSerialize() : array
    {
        $ugroup = [];
        foreach ($this->groups as $group) {
            $ugroup[] = Uuid::fromBytes($group);
        }
        return ['id' => $this->id, 'name' => $this->name, 'role' => $this->role, 'groups' => $ugroup];
    }
}
