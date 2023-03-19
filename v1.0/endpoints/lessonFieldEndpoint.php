<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;

$lessonFieldEndpoint = new Endpoint();

$updateLessonField = function (Skautis $skautis, array $data) : array {
    $deleteSQL = <<<SQL
DELETE FROM `lessons_in_fields`
WHERE `lesson_id` = :lesson_id
LIMIT 1;
SQL;
    $insertSQL = <<<SQL
INSERT INTO `lessons_in_fields` (`field_id`, `lesson_id`)
VALUES (:field_id, :lesson_id);
SQL;

    $lessonId = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();
    if (isset($data['field']) and $data['field'] !== '') {
        $fieldId = Helper::parseUuid($data['field'], 'field')->getBytes();
    }

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteSQL);
    $db->bindParam(':lesson_id', $lessonId, PDO::PARAM_STR);
    $db->execute();

    if (isset($fieldId)) {
        $db->prepare($insertSQL);
        $db->bindParam(':field_id', $fieldId, PDO::PARAM_STR);
        $db->bindParam(':lesson_id', $lessonId, PDO::PARAM_STR);
        $db->execute("lesson or field");
    }
    $db->endTransaction();
    return ['status' => 200];
};
$lessonFieldEndpoint->setUpdateMethod(new Role('editor'), $updateLessonField);
