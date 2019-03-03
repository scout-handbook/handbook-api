<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v0_9\Exception\LockedException;

$mutexEndpoint = new Endpoint();

$addMutex = function (Skautis $skautis, array $data) : array {
    $selectSQL = <<<SQL
SELECT DISTINCT UNIX_TIMESTAMP(mutexes.timeout), mutexes.holder, users.name
FROM mutexes
LEFT JOIN users ON mutexes.holder = users.id
WHERE mutexes.id = :id;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM mutexes
WHERE id = :id;
SQL;
    $insertSQL = <<<SQL
INSERT INTO mutexes (id, timeout, holder)
VALUES (:id, FROM_UNIXTIME(:timeout), :holder);
SQL;

    $id = Helper::parseUuid($data['id'], 'resource')->getBytes();
    $timeout = time() + 1800;
    if (isset($_COOKIE['skautis_timeout'])) {
        $timeout = ctype_digit($_COOKIE['skautis_timeout']) ? intval($_COOKIE['skautis_timeout']) : null;
        if ($timeout === null || abs($timeout - time()) > 3600) {
            throw new InvalidArgumentTypeException('number', ['Unix timestamp']);
        }
    }
    $userId = $skautis->UserManagement->LoginDetail()->ID_Person;

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $origTimeout = '';
    $origHolder = '';
    $origHolderName = '';
    $db->bindColumn(1, $origTimeout);
    $db->bindColumn('holder', $origHolder);
    $db->bindColumn('name', $origHolderName);
    if ($db->fetch() && $origHolder != $userId && $origTimeout > time()) {
        throw new LockedException($origHolderName);
    }

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($insertSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':timeout', $timeout, PDO::PARAM_INT);
    $db->bindParam(':holder', $userId, PDO::PARAM_INT);
    $db->execute();

    $db->endTransaction();
    return ['status' => 201];
};
$mutexEndpoint->setAddMethod(new Role('editor'), $addMutex);

$extendMutex = function (Skautis $skautis, array $data) : array {
    $selectSQL = <<<SQL
SELECT 1
FROM mutexes
WHERE id = :id AND holder = :holder;
SQL;
    $updateSQL = <<<SQL
UPDATE mutexes
SET timeout = FROM_UNIXTIME(:timeout)
WHERE id = :id AND holder = :holder
LIMIT 1;
SQL;

    $id = Helper::parseUuid($data['id'], 'resource')->getBytes();
    $timeout = time() + 1800;
    if (isset($_COOKIE['skautis_timeout'])) {
        $timeout = ctype_digit($_COOKIE['skautis_timeout']) ? intval($_COOKIE['skautis_timeout']) : null;
        if ($timeout === null || abs($timeout - time()) > 3600) {
            throw new InvalidArgumentTypeException('number', ['Unix timestamp']);
        }
    }
    $userId = $skautis->UserManagement->LoginDetail()->ID_Person;

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':holder', $userId, PDO::PARAM_INT);
    $db->execute();
    $db->fetchRequire('mutex');

    $db->prepare($updateSQL);
    $db->bindParam(':timeout', $timeout, PDO::PARAM_INT);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':holder', $userId, PDO::PARAM_INT);
    $db->execute();

    $db->endTransaction();
    return ['status' => 200];
};
$mutexEndpoint->setUpdateMethod(new Role('editor'), $extendMutex);

$releaseMutex = function (Skautis $skautis, array $data) : array {
    $selectSQL = <<<SQL
SELECT 1
FROM mutexes
WHERE id = :id AND holder = :holder;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM mutexes
WHERE id = :id AND holder = :holder;
SQL;

    $id = Helper::parseUuid($data['id'], 'resource')->getBytes();
    $userId = $skautis->UserManagement->LoginDetail()->ID_Person;

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':holder', $userId, PDO::PARAM_INT);
    $db->execute();
    $db->fetchRequire('mutex');

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':holder', $userId, PDO::PARAM_INT);
    $db->execute();
    
    $db->endTransaction();
    return ['status' => 200];
};
$mutexEndpoint->setDeleteMethod(new Role('editor'), $releaseMutex);
