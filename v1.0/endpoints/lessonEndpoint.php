<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v1.0/endpoints/lessonCompetenceEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonFieldEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonGroupEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonHistoryEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/lessonPDFEndpoint.php');
require_once($CONFIG->basepath . '/v1.0/endpoints/mutexEndpoint.php');

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Lesson;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;
use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;
use Skaut\HandbookAPI\v1_0\Exception\NotLockedException;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;

$lessonEndpoint = new Endpoint();
$lessonEndpoint->addSubEndpoint('competence', $lessonCompetenceEndpoint);
$lessonEndpoint->addSubEndpoint('field', $lessonFieldEndpoint);
$lessonEndpoint->addSubEndpoint('group', $lessonGroupEndpoint);
$lessonEndpoint->addSubEndpoint('history', $lessonHistoryEndpoint);
$lessonEndpoint->addSubEndpoint('pdf', $lessonPDFEndpoint);

$listLessons = function (Skautis $skautis, array $data) : array {
    $lessonSQL = <<<SQL
SELECT `id`, `name`, UNIX_TIMESTAMP(`version`)
FROM `lessons`;
SQL;
    $competenceSQL = <<<SQL
SELECT `competence_id`
FROM `competences_for_lessons`
WHERE `lesson_id` = :lesson_id;
SQL;

    $overrideGroup = (isset($data['override-group']) and $data['override-group'] == 'true');

    $lessons = [];

    $db = new Database();
    $db->prepare($lessonSQL);
    $db->execute();
    $lesson_id = '';
    $lesson_name = '';
    $lesson_version = '';
    $db->bindColumn('id', $lesson_id);
    $db->bindColumn('name', $lesson_name);
    $db->bindColumn(3, $lesson_version);

    while ($db->fetch()) {
        if (!Helper::checkLessonGroup(Uuid::fromBytes($lesson_id), $overrideGroup)) {
            continue;
        }
        $newLesson = new Lesson($lesson_name, floatval($lesson_version));

        $db2 = new Database();
        $db2->prepare($competenceSQL);
        $db2->bindParam(':lesson_id', $lesson_id, PDO::PARAM_STR);
        $db2->execute();
        $competence_id = '';
        $db2->bindColumn('competence_id', $competence_id);
        while ($db2->fetch()) {
            $newLesson->addCompetence($competence_id);
        }

        $lessons[Uuid::fromBytes($lesson_id)->toString()] = $newLesson;
    }
    return ['status' => 200, 'response' => $lessons];
};
$lessonEndpoint->setListMethod(new Role('guest'), $listLessons);

$getLesson = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
SELECT `body`
FROM `lessons`
WHERE `id` = :id;
SQL;

    $id = Helper::parseUuid($data['id'], 'lesson');

    if (!Helper::checkLessonGroup($id, true)) {
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
$lessonEndpoint->setGetMethod(new Role('guest'), $getLesson);

$addLesson = function (Skautis $skautis, array $data) : array {
    $SQL = <<<SQL
INSERT INTO `lessons` (`id`, `name`, `body`)
VALUES (:id, :name, :body);
SQL;

    if (!isset($data['name'])) {
        throw new MissingArgumentException(MissingArgumentException::POST, 'name');
    }
    $name = $data['name'];
    $body = '';
    if (isset($data['body'])) {
        $body = $data['body'];
    }
    $uuid = Uuid::uuid4();
    $id = $uuid->getBytes();

    $db = new Database();
    $db->prepare($SQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':body', $body, PDO::PARAM_STR);
    $db->execute();
    return ['status' => 201, 'response' => $uuid];
};
$lessonEndpoint->setAddMethod(new Role('editor'), $addLesson);

$updateLesson = function (Skautis $skautis, array $data) : array {
    $selectSQL = <<<SQL
SELECT `name`, `body`
FROM `lessons`
WHERE `id` = :id;
SQL;
    $copySQL = <<<SQL
INSERT INTO `lesson_history` (`id`, `name`, `version`, `body`)
SELECT `id`, `name`, `version`, `body`
FROM `lessons`
WHERE `id` = :id;
SQL;
    $updateSQL = <<<SQL
UPDATE `lessons`
SET `name` = :name, `version` = CURRENT_TIMESTAMP(3), `body` = :body
WHERE `id` = :id
LIMIT 1;
SQL;

    global $mutexEndpoint;
    try {
        $mutexEndpoint->call('DELETE', new Role('editor'), ['id' => $data['id']]);
    } catch (NotFoundException $e) {
        throw new NotLockedException();
    }

    $id = Helper::parseUuid($data['id'], 'lesson')->getBytes();
    if (isset($data['name'])) {
        $name = $data['name'];
    }
    if (isset($data['body'])) {
        $body = $data['body'];
    }

    $db = new Database();

    if (!isset($name) or !isset($body)) {
        $db->prepare($selectSQL);
        $db->bindParam(':id', $id, PDO::PARAM_STR);
        $db->execute();
        $origName = '';
        $origBody = '';
        $db->bindColumn('name', $origName);
        $db->bindColumn('body', $origBody);
        if (!$db->fetch()) {
            throw new NotFoundException('lesson');
        }
        if (!isset($name)) {
            $name = $origName;
        }
        if (!isset($body)) {
            $body = $origBody;
        }
    }

    $db->beginTransaction();

    $db->prepare($copySQL);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    $db->prepare($updateSQL);
    $db->bindParam(':name', $name, PDO::PARAM_STR);
    $db->bindParam(':body', $body, PDO::PARAM_STR);
    $db->bindParam(':id', $id, PDO::PARAM_STR);
    $db->execute();

    if ($db->rowCount() != 1) {
        throw new NotFoundException("lesson");
    }

    $db->endTransaction();
    return ['status' => 200];
};
$lessonEndpoint->setUpdateMethod(new Role('editor'), $updateLesson);

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
$lessonEndpoint->setDeleteMethod(new Role('administrator'), $deleteLesson);
