<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;

$lessonHistoryEndpoint = new Endpoint();

$listLessonHistory = function (Skautis $skautis, array $data): array {
    $checkSQL = <<<SQL
SELECT 1 FROM `lessons`
WHERE `id` = :id
LIMIT 1;
SQL;
    $selectSQL = <<<SQL
SELECT `name`, UNIX_TIMESTAMP(`version`) FROM `lesson_history`
WHERE `id` = :id
ORDER BY `version` DESC;
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();

    $db = new Database();
    $db->prepare($checkSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $db->fetchRequire('lesson');

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $versions = [];
    $name = '';
    $version = '';
    $db->bindColumn('name', $name);
    $db->bindColumn(2, $version);
    while ($db->fetch()) {
        $versions[] = ['name' => $name, 'version' => round($version * 1000)];
    }
    return ['status' => 200, 'response' => $versions];
};
$lessonHistoryEndpoint->setListMethod(new Role('editor'), $listLessonHistory);

$getLessonHistory = function (Skautis $skautis, array $data): array {
    $checkSQL = <<<SQL
SELECT 1 FROM `lessons`
WHERE `id` = :id
LIMIT 1;
SQL;
    $selectSQL = <<<SQL
SELECT `body`
FROM `lesson_history`
WHERE `id` = :id
AND `version` = FROM_UNIXTIME(:version);
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();
    $version = ctype_digit($data['id']) ? intval($data['id']) / 1000 : null;
    if ($version === null) {
        throw new InvalidArgumentTypeException('number', ['Integer']);
    }
    $version = strval($version);

    $db = new Database();
    $db->prepare($checkSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $db->fetchRequire('lesson');

    $db->prepare($selectSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':version', $version, PDO::PARAM_STR);
    $db->execute();
    $body = '';
    $db->bindColumn('body', $body);
    $db->fetchRequire('lesson');
    return ['status' => 200, 'response' => $body];
};
$lessonHistoryEndpoint->setGetMethod(new Role('editor'), $getLessonHistory);
