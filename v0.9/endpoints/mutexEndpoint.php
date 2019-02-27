<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Endpoint.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

require_once($CONFIG->basepath . '/v0.9/internal/exceptions/InvalidArgumentTypeException.php');
require_once($CONFIG->basepath . '/v0.9/internal/exceptions/LockedException.php');

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Helper;

$mutexEndpoint = new HandbookAPI\Endpoint();

$addMutex = function (Skautis\Skautis $skautis, array $data) : array {
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
            throw new HandbookAPI\InvalidArgumentTypeException('number', ['Unix timestamp']);
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
        throw new HandbookAPI\LockedException($origHolderName);
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
$mutexEndpoint->setAddMethod(new HandbookAPI\Role('editor'), $addMutex);

$extendMutex = function (Skautis\Skautis $skautis, array $data) : array {
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
            throw new HandbookAPI\InvalidArgumentTypeException('number', ['Unix timestamp']);
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
$mutexEndpoint->setUpdateMethod(new HandbookAPI\Role('editor'), $extendMutex);

$releaseMutex = function (Skautis\Skautis $skautis, array $data) : array {
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
$mutexEndpoint->setDeleteMethod(new HandbookAPI\Role('editor'), $releaseMutex);
