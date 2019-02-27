<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Endpoint.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Helper;

$mutexBeaconEndpoint = new HandbookAPI\Endpoint();

$releaseBeaconMutex = function (Skautis\Skautis $skautis, array $data) : void {
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
$mutexBeaconEndpoint->setAddMethod(new HandbookAPI\Role('editor'), $releaseBeaconMutex);
