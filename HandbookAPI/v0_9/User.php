<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;

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
