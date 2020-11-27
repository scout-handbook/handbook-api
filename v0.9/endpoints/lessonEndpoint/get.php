<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Exception\RoleException;

$getLesson = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
SELECT `body`
FROM `lessons`
WHERE `id` = :id;
SQL;

    $id = Helper::parseUuid($data['id'], 'lesson');

    if (!checkLessonGroup($id, true)) {
        throw new RoleException();
    }

    $id = $id->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();
    $body = '';
    $db->bindColumn('body', $body);
    $db->fetchRequire('lesson');
    return ['status' => 200, 'response' => $body];
};
