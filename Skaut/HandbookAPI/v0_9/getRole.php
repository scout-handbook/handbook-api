<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

function getRole(int $idPerson) : Role
{
    $SQL = <<<SQL
SELECT role
FROM users
WHERE id = :id;
SQL;

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $idPerson, \PDO::PARAM_INT);
    $db->execute();
    $role = '';
    $db->bindColumn('role', $role);
    $db->fetchRequire('user');
    return new Role(strval($role));
}
