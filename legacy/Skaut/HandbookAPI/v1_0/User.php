<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or exit('Restricted access.');

use Ramsey\Uuid\Uuid;

class User implements \JsonSerializable
{
    private $id;

    private $name;

    private $role;

    private $groups;

    public function __construct(int $id, string $name, string $role)
    {
        $this->id = $id;
        $this->name = Helper::xssSanitize($name);
        $this->role = new Role($role);
        $this->groups = [];
    }

    public function addGroup(string $group): void
    {
        $this->groups[] = Uuid::fromBytes($group);
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'name' => $this->name, 'role' => $this->role, 'groups' => $this->groups];
    }
}
