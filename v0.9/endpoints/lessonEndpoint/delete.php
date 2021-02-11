<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;
use Skaut\HandbookAPI\v0_9\Exception\NotFoundException;
use Skaut\HandbookAPI\v0_9\Exception\NotLockedException;

$deleteLesson = function (Skautis $skautis, array $data) : array {
    $copySQL = <<<SQL
INSERT INTO `lesson_history` (`id`, `name`, `version`, `body`)
SELECT `id`, `name`, `version`, `body`
FROM `lessons`
WHERE `id` = :id;
SQL;
    $deleteFieldSQL = <<<SQL
DELETE FROM `lessons_in_fields`
WHERE `lesson_id` = :lesson_id;
SQL;
    $deleteCompetencesSQL = <<<SQL
DELETE FROM `competences_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;
    $deleteGroupsSQL = <<<SQL
DELETE FROM `groups_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;
    $deleteSQL = <<<SQL
DELETE FROM `lessons`
WHERE `id` = :id;
SQL;

    global $mutexEndpoint;
    try {
        $mutexEndpoint->call('DELETE', new Role('editor'), ['id' => $data['id']]);
    } catch (NotFoundException $e) {
        throw new NotLockedException();
    }

    $id = Helper::parseUuid($data['id'], 'lesson')->getBytes();

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($copySQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteFieldSQL);
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteCompetencesSQL);
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteGroupsSQL);
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($deleteSQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("lesson");
    }

    $db->endTransaction();
    return ['status' => 200];
};
