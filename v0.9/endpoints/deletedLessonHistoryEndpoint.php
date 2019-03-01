<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;

$deletedLessonHistoryEndpoint = new Endpoint();

$listDeletedLessonHistory = function (Skautis\Skautis $skautis, array $data) : array {
    $checkSQL = <<<SQL
SELECT 1 FROM lessons
WHERE id = :id
LIMIT 1;
SQL;
    $selectSQL = <<<SQL
SELECT name, UNIX_TIMESTAMP(version) FROM lesson_history
WHERE id = :id
ORDER BY version DESC;
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'deleted lesson')->getBytes();

    $db = new Database();
    $db->prepare($checkSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    if ($db->fetch()) {
        throw new NotFoundException('deleted lesson');
    }

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $versions = [];
    $name = '';
    $version = '';
    $db->bindColumn('name', $name);
    $db->bindColumn(2, $version);
    if (!$db->fetch()) {
        throw new NotFoundException('deleted lesson');
    }
    $versions[] = ['name' => $name, 'version' => round($version * 1000)];
    while ($db->fetch()) {
        $versions[] = ['name' => $name, 'version' => round($version * 1000)];
    }
    return ['status' => 200, 'response' => $versions];
};
$deletedLessonHistoryEndpoint->setListMethod(new HandbookAPI\Role('administrator'), $listDeletedLessonHistory);

$getDeletedLessonHistory = function (Skautis\Skautis $skautis, array $data) : array {
    $checkSQL = <<<SQL
SELECT 1 FROM lessons
WHERE id = :id
LIMIT 1;
SQL;
    $selectSQL = <<<SQL
SELECT body
FROM lesson_history
WHERE id = :id
AND version = FROM_UNIXTIME(:version);
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'deleted lesson')->getBytes();
    $version = ctype_digit($data['id']) ? intval($data['id']) / 1000 : null;
    if ($version === null) {
        throw new InvalidArgumentTypeException('number', ['Integer']);
    }
    $version = strval($version);

    $db = new Database();
    $db->prepare($checkSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    if ($db->fetch()) {
        throw new NotFoundException('deleted lesson');
    }

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':version', $version, PDO::PARAM_STR);
    $db->execute();
    $body = '';
    $db->bindColumn('body', $body);
    $db->fetchRequire('deleted lesson');
    return ['status' => 200, 'response' => $body];
};
$deletedLessonHistoryEndpoint->setGetMethod(new HandbookAPI\Role('administrator'), $getDeletedLessonHistory);
