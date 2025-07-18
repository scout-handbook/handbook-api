<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0;

@_API_EXEC === 1 or exit('Restricted access.');

class Role implements \JsonSerializable
{
    private const GUEST = 0;

    private const USER = 1;

    private const EDITOR = 2;

    private const ADMINISTRATOR = 3;

    private const SUPERUSER = 4;

    private $role;

    public function __construct(string $str)
    {
        switch ($str) {
            case 'superuser':
                $this->role = self::SUPERUSER;
                break;
            case 'administrator':
                $this->role = self::ADMINISTRATOR;
                break;
            case 'editor':
                $this->role = self::EDITOR;
                break;
            case 'user':
                $this->role = self::USER;
                break;
            default:
                $this->role = self::GUEST;
                break;
        }
    }

    public function __toString(): string
    {
        switch ($this->role) {
            case self::SUPERUSER:
                return 'superuser';
            case self::ADMINISTRATOR:
                return 'administrator';
            case self::EDITOR:
                return 'editor';
            case self::USER:
                return 'user';
            default:
                return 'guest';
        }
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    public static function get(int $idPerson): Role
    {
        $SQL = <<<'SQL'
SELECT `role`
FROM `users`
WHERE `id` = :id;
SQL;

        $db = new Database;
        $db->prepare($SQL);
        $db->bindParam(':id', $idPerson, \PDO::PARAM_INT);
        $db->execute();
        $role = '';
        $db->bindColumn('role', $role);
        $db->fetchRequire('user');

        return new Role(strval($role));
    }

    public static function compare(Role $first, Role $second): int
    {
        return $first->role <=> $second->role;
    }
}
