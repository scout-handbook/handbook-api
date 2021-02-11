<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;
use Skaut\HandbookAPI\v0_9\Helper;
use Skaut\HandbookAPI\v0_9\Role;

$lessonCompetenceEndpoint = new Endpoint();

$updateLessonCompetence = function (Skautis $skautis, array $data) : array {
    $deleteSQL = <<<SQL
DELETE FROM `competences_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;
    $insertSQL = <<<SQL
INSERT INTO `competences_for_lessons` (`lesson_id`, `competence_id`)
VALUES (:lesson_id, :competence_id);
SQL;

    $id = Helper::parseUuid($data['parent-id'], 'lesson')->getBytes();
    $competences = [];
    if (isset($data['competence'])) {
        foreach ($data['competence'] as $competence) {
            $competences[] = Helper::parseUuid($competence, 'competence')->getBytes();
        }
    }

    $db = new Database();
    $db->beginTransaction();

    $db->prepare($deleteSQL);
    $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($insertSQL);
    foreach ($competences as $competence) {
        $db->bindParam(':lesson_id', $id, PDO::PARAM_STR);
        $db->bindParam(':competence_id', $competence, PDO::PARAM_STR);
        $db->execute("lesson or competence");
    }
    $db->endTransaction();
    return ['status' => 200];
};
$lessonCompetenceEndpoint->setUpdateMethod(new Role('editor'), $updateLessonCompetence);
