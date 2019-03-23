<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;

$mutexBeaconEndpoint = new Endpoint();

$releaseBeaconMutex = function (Skautis $skautis, array $data) : void {
    $selectSQL = <<<SQL
SELECT 1
FROM mutexes
WHERE id = :id AND holder = :holder;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM mutexes
WHERE id = :id AND holder = :holder;
SQL;

    try {
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
    } catch (Exception $e) {
        die();
    }
    die();
};
$mutexBeaconEndpoint->setAddMethod(new Role('editor'), $releaseBeaconMutex);
