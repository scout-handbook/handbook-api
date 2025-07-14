<?php

declare(strict_types=1);

namespace App;

use PDO;
use Skaut\HandbookAPI\v1_0\Database;

// phpcs:disable PHPCompatibility

enum UserRole: string
{
    case Guest = 'guest';
    case User = 'user';
    case Editor = 'editor';
    case Administrator = 'administrator';
    case Superuser = 'superuser';

    // TODO: Convert to Eloquent
    public static function get(int $idPerson): self
    {
        $db = new Database;
        $db->prepare(<<<'SQL'
SELECT `role`
FROM `users`
WHERE `id` = :id;
SQL);
        $db->bindParam(':id', $idPerson, PDO::PARAM_INT);
        $db->execute();
        $role = '';
        $db->bindColumn('role', $role);
        $db->fetchRequire('user');

        return self::from($role);
    }

    public static function compare(self $a, self $b): int
    {
        return $a->toInt() <=> $b->toInt();
    }

    private function toInt(): int
    {
        return match ($this) {
            self::Guest => 0,
            self::User => 1,
            self::Editor => 2,
            self::Administrator => 3,
            self::Superuser => 4,
        };
    }
}
