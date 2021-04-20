<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;

$lessonGroupEndpoint = new Endpoint();

$listLessonGroups = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
SELECT `group_id` FROM `groups_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;

    $db = new Database();
    $db->prepare($SQL);
    $id = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();
    $groups = [];
    $group_id = '';
    $db->bindColumn('group_id', $group_id);
    while ($db->fetch()) {
        $groups[] = Uuid::fromBytes(strval($group_id));
    }
    return ['status' => 200, 'response' => $groups];
};
$lessonGroupEndpoint->setListMethod(new Role('editor'), $listLessonGroups);

$updateLessonGroups = function (Skautis $skautis, array $data) : array {
    $deleteSQL = <<<SQL
DELETE FROM `groups_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;
    $insertSQL = <<<SQL
INSERT INTO `groups_for_lessons` (`lesson_id`, `group_id`)
VALUES (:lesson_id, :group_id);
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();
    $groups = [];
    if (isset($data['group'])) {
        foreach ($data['group'] as $group) {
            $groups[] = Helper::parseUuid($group, 'group')->getBytes();
        }
    }

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteSQL);
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($insertSQL);
    foreach ($groups as $group) {
        $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
        $db->bindParam(':group_id', $group, PDO::PARAM_STR);
        $db->execute("lesson or group");
    }
    $db->endTransaction();
    return ['status' => 200];
};
$lessonGroupEndpoint->setUpdateMethod(new Role('editor'), $updateLessonGroups);
