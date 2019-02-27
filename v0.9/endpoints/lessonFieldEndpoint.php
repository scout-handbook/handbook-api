<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

use Ramsey\Uuid\Uuid;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;

$lessonFieldEndpoint = new Endpoint();

$updateLessonField = function (Skautis\Skautis $skautis, array $data) : array {
    $deleteSQL = <<<SQL
DELETE FROM lessons_in_fields
WHERE lesson_id = :lesson_id
LIMIT 1;
SQL;
    $insertSQL = <<<SQL
INSERT INTO lessons_in_fields (field_id, lesson_id)
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
$lessonFieldEndpoint->setUpdateMethod(new HandbookAPI\Role('editor'), $updateLessonField);
